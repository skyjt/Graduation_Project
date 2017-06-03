package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.os.StrictMode;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ImageButton;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.amap.api.maps.CoordinateConverter;
import com.amap.api.maps.model.LatLng;
import com.amap.api.services.core.AMapException;
import com.amap.api.services.core.LatLonPoint;
import com.amap.api.services.geocoder.GeocodeResult;
import com.amap.api.services.geocoder.GeocodeSearch;

import com.amap.api.services.geocoder.RegeocodeAddress;
import com.amap.api.services.geocoder.RegeocodeQuery;
import com.amap.api.services.geocoder.RegeocodeResult;
import com.unmanedship.lzxle.unmanned.Adapter.MyBaseAdapter;
import com.unmanedship.lzxle.unmanned.Adapter.Text_bean;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

/**
 * Created by lzxle on 2017/3/31.
 */

public class ShipList extends Activity implements GeocodeSearch.OnGeocodeSearchListener {
    private ListView mlistview;
    private List<Text_bean> mDatas = null;
    private List<LatLonPoint> points = new ArrayList<LatLonPoint>();
    private MyBaseAdapter mBaseAdapter;
    private TextView main_title;
    private JSONArray jsonArray;
    private ArrayList addr =  new ArrayList();
    private ArrayList shipid =  new ArrayList();
    private ArrayList ship =  new ArrayList();
    private ArrayList time = new ArrayList();
    private ArrayList state = new ArrayList();
    private Handler handler = new Handler();
    private Handler mHandler = null;
    private ImageButton mSetting;
    private GeocodeSearch geocoderSearch;
    private int count = 0;
    private int sum = -1;
    private ImageButton imageButton;
    private LatLonPoint point;
    private ExecutorService mExecutorService;
    private String username;
    private SharedPreferences sharedPreferences;
    public static ShipList _instance = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        geocoderSearch = new GeocodeSearch(this);
        mDatas = new ArrayList<Text_bean>();
        mBaseAdapter = new MyBaseAdapter(this,mDatas);
        initViews();
        initDatas();

        Thread check = new Thread(new Runnable() {
            @Override
            public void run() {
                boolean flag = true;
                while (flag){
                    if(count==sum) {
                        Log.i("tag","run");
                        Text_bean bean;
                        for (int i = 0;i < ship.size(); i++){
                            bean = new Text_bean(ship.get(i).toString(),state.get(i).toString(), time.get(i).toString(),"地点："+addr.get(i).toString());
                            mDatas.add(bean);
                        }
                        Message message = Message.obtain(mHandler,1);
                        mHandler.sendMessage(message);
                        flag = false;
                    }
                }
            }
        });
        check.start();

        mHandler = new Handler(){
            @Override
            public void handleMessage(Message msg){
                switch (msg.what){
                    case 0:
                        getAddresses();
                        break;
                    case 1:
                        mBaseAdapter.notifyDataSetChanged();
                        break;
                    default:
                        break;
                }
            }
        };

        mlistview.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(ShipList.this,Detail_info.class);
                Bundle bundle = new Bundle();
                bundle.putString("ship_name",ship.get(position).toString());
                bundle.putInt("ship_id",(int)shipid.get(position));
                intent.putExtras(bundle);
                startActivity(intent);
                finish();
            }
        });
        mSetting.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(ShipList.this,Setting.class);
                startActivity(intent);
            }
        });
    }
    private void getAddresses(){
        if (mExecutorService == null) {
            mExecutorService = Executors.newSingleThreadExecutor();
        }
        List<LatLonPoint> geopointlist = points;
        Text_bean bean;
        for (final LatLonPoint po : geopointlist) {
            mExecutorService.submit(new Runnable() {
                @Override
                public void run() {
                    try {
                        //LatLonPoint getPosition = new LatLonPoint();
                        LatLonPoint takeposition = Tools.LatLonPointconvert(po, CoordinateConverter.CoordType.GPS,ShipList.this);
                        RegeocodeQuery query = new RegeocodeQuery(takeposition, 20,
                                GeocodeSearch.AMAP);// 第一个参数表示一个Latlng，第二参数表示范围多少米，第三个参数表示是火系坐标系还是GPS原生坐标系
                        RegeocodeAddress result = geocoderSearch.getFromLocation(query);// 设置同步逆地理编码请求
                        addr.add(result.getFormatAddress());


                    }catch (AMapException e){
                        addr.add("null");
                        e.printStackTrace();
                    }
                    count ++;
                }
            });
        }

    }


    private void initViews() {
        mlistview = (ListView) findViewById(R.id.ship_list);
        mlistview.setAdapter(mBaseAdapter);
        main_title = (TextView) findViewById(R.id.main_title);
        mSetting = (ImageButton) findViewById(R.id.setting);
        _instance = this;
        geocoderSearch = new GeocodeSearch(this);
        geocoderSearch.setOnGeocodeSearchListener(this);
        sharedPreferences = getSharedPreferences("login_info",MODE_PRIVATE);    //初始化sharedPreferences
        username = sharedPreferences.getString("username","");

    }

    private void initDatas() {          //数据初始化
        Thread t = new Thread(new Runnable() {              //将网络获取放入非主线程中，防止网络问题导致卡死
            @Override
            public void run() {
                final String response = NetConnect.ship_list(username);
                try {
                    jsonArray = new JSONArray(response);
                    sum = jsonArray.length();
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject jsonObject = jsonArray.optJSONObject(i);
                        ship.add(jsonObject.optString("shipname"));
                        time.add(jsonObject.optString("time"));
                        double lat = jsonObject.optDouble("lat");
                        double lon = jsonObject.optDouble("lon");
                        point = new LatLonPoint(lat,lon);
                        points.add(point);
                        shipid.add(jsonObject.optInt("id"));
                        SimpleDateFormat ftime = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                        Date d = ftime.parse(jsonObject.optString("time"));
                        long date = d.getTime();
                        long cz = System.currentTimeMillis() - date;
                        if (cz >= 100000) state.add("离线");
                        else state.add("在线");

                    }
                    Message message = Message.obtain(mHandler,0);
                    mHandler.sendMessage(message);
                }catch (Exception e){
                    e.printStackTrace();
                }

            }
        });
        t.start();
    }



    @Override
    public void onRegeocodeSearched(RegeocodeResult regeocodeResult, int i) {

    }

    @Override
    public void onGeocodeSearched(GeocodeResult geocodeResult, int i) {

    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (mExecutorService != null){
            mExecutorService.shutdownNow();
        }
    }
}
