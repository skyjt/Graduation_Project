package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.amap.api.maps.AMap;
import com.amap.api.maps.CoordinateConverter;
import com.amap.api.maps.model.LatLng;
import com.amap.api.services.core.AMapException;
import com.amap.api.services.core.LatLonPoint;
import com.amap.api.services.geocoder.GeocodeResult;
import com.amap.api.services.geocoder.GeocodeSearch;
import com.amap.api.services.geocoder.RegeocodeAddress;
import com.amap.api.services.geocoder.RegeocodeQuery;
import com.amap.api.services.geocoder.RegeocodeResult;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;
import java.util.logging.LogRecord;

import static android.content.ContentValues.TAG;

/**
 * Created by lzxle on 2017/5/9.
 */

public class Detail_info extends Activity implements GeocodeSearch.OnGeocodeSearchListener {
    private TextView mShipname,mLasttime,mShipstatus,mLon,mLat,mLocation,mTemp,mOxy,mPh;
    private GeocodeSearch geocoderSearch;
    private Button mMap,mHistory_loc,mHistory_info;
    private String shipname;
    private String lasttime;
    private String shipstatus;
    private double lon;
    private double lat;
    private String location;
    private double temp;
    private double oxy;
    private double ph;
    private int ship_id;
    private Timer timer = null;
    private LatLonPoint point;
    private Handler mHandler = null;
    private TimerTask mTimerTask = null; //定时任务
    private boolean checkTimer = false;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.detail_info);
        checkTimer = true;
        initViews();
        Bundle bundle = this.getIntent().getExtras();
        shipname = bundle.getString("ship_name");
        ship_id = bundle.getInt("ship_id");
        mShipname.setText("船名："+shipname);

        initData();
        startRefresh();

        mHandler = new Handler(){
            @Override
            public void handleMessage(Message msg) {
                switch (msg.what) {
                    case 1:
                        updateText();
                        break;
                    case 0:
                        LatLonPoint position = Tools.LatLonPointconvert(point, CoordinateConverter.CoordType.GPS,Detail_info.this);
                        RegeocodeQuery query = new RegeocodeQuery(position, 20, GeocodeSearch.AMAP);// 第一个参数表示一个Latlng，第二参数表示范围多少米，第三个参数表示是火系坐标系还是GPS原生坐标系
                        geocoderSearch.getFromLocationAsyn(query);
                        updateTextView();
                        break;
                    default:
                        break;
                }
            }
        };

        mMap.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent  = new Intent(Detail_info.this,Map_Loc.class);
                Bundle bundle1 = new Bundle();
                bundle1.putInt("ship_id",ship_id);
                intent.putExtras(bundle1);
                startActivity(intent);
            }
        });
        mHistory_loc.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(Detail_info.this,map_trace.class);
                Bundle bundle2 = new Bundle();
                bundle2.putInt("ship_id",ship_id);
                intent.putExtras(bundle2);
                startActivity(intent);
            }
        });

        mHistory_info.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(Detail_info.this,Time_pick.class);
                Bundle bundle3 = new Bundle();
                bundle3.putInt("ship_id",ship_id);
                intent.putExtras(bundle3);
                startActivity(intent);
            }
        });
    }

    @Override
    public void onRegeocodeSearched(RegeocodeResult regeocodeResult, int i) {
        location = regeocodeResult.getRegeocodeAddress().getFormatAddress();
        Log.i("test",location);
        Message message = Message.obtain(mHandler, 1);
        mHandler.sendMessage(message);
    }

    @Override
    public void onGeocodeSearched(GeocodeResult result, int rCode) {
    }
    private void updateTextView() {
        mLasttime.setText("刷新时间："+lasttime);
        mShipstatus.setText("状态："+shipstatus);
        mLon.setText("经度："+lon);
        mLat.setText("纬度："+lat);
        mTemp.setText("温度："+temp);
        mOxy.setText("含氧量："+oxy+"mg/L");
        mPh.setText("酸碱值："+ph);
    }
    private void updateText(){
        mLocation.setText("方位："+location);
    }

    private void initViews() {      //初始化视图
        mShipname = (TextView) findViewById(R.id.shipname);      //无人船id
        mLasttime = (TextView) findViewById(R.id.lasttime);      //数据最后的时间
        mShipstatus = (TextView) findViewById(R.id.shipstatus);  //状态
        mLon = (TextView) findViewById(R.id.lon);           //经纬度
        mLat = (TextView) findViewById(R.id.lat);
        mLocation = (TextView) findViewById(R.id.location);      //地点
        mTemp = (TextView) findViewById(R.id.temp);              //温度
        mOxy = (TextView) findViewById(R.id.oxy);                //氧气含量
        mPh = (TextView) findViewById(R.id.ph);                  //ph值
        mMap = (Button) findViewById(R.id.map);                  //地图上显示当前地点
        mHistory_loc = (Button) findViewById(R.id.history_loc);//历史位置轨迹
        mHistory_info = (Button) findViewById(R.id.history_info);
        geocoderSearch = new GeocodeSearch(this);
        geocoderSearch.setOnGeocodeSearchListener(this);

    }

    private void initData(){
        Thread t = new Thread(new Runnable() {
            @Override
            public void run() {
                getData();
            }
        });
        t.start();
    }

    private void getData(){
        String response = NetConnect.get("http://112.74.213.181:88/select_detailinfo.php?ship_id="+ship_id);
        try {
            JSONArray jsonArray = new JSONArray(response);
            JSONObject jsonObject = jsonArray.optJSONObject(0);
            lasttime = jsonObject.optString("time");
            lon = jsonObject.optDouble("lon");
            lat = jsonObject.optDouble("lat");
            temp = jsonObject.optDouble("temp");
            oxy = jsonObject.optDouble("oxy");
            ph = jsonObject.optDouble("ph");

            point = new LatLonPoint(lat,lon);
            SimpleDateFormat ftime = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            Date d = ftime.parse(lasttime);
            long date = d.getTime();
            long cz = System.currentTimeMillis() - date;
            if (cz >= 100000) shipstatus = "离线";
            else shipstatus = "在线";
            Message message = Message.obtain(mHandler, 0);
            mHandler.sendMessage(message);

        } catch (JSONException | ParseException e) {
            e.printStackTrace();
        }

    }

    private void startRefresh(){        //刷新页面任务
        if(timer == null){
            timer = new Timer();
        }
        if(mTimerTask == null){
            mTimerTask = new TimerTask() {
                @Override
                public void run() {
                    getData();
                }
            };
        }
        if(timer != null && mTimerTask != null){
            timer.schedule(mTimerTask, 3000, 3000);
        }
    }

    private void stopTimer(){
        if (timer != null) {
            timer.cancel();
            timer = null;
        }
        if (mTimerTask != null) {
            mTimerTask.cancel();
            mTimerTask = null;
        }
        Log.i("info","timer停止");
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent intent = new Intent(this,ShipList.class);
        startActivity(intent);
        finish();
    }

    @Override
    protected void onResume() {
        super.onResume();
        if(!checkTimer){
            startRefresh();
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
        stopTimer();
        checkTimer = false;
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        stopTimer();
        checkTimer = false;

    }

    @Override
    protected void onStop() {
        super.onStop();
        stopTimer();
        checkTimer = false;
    }
}
