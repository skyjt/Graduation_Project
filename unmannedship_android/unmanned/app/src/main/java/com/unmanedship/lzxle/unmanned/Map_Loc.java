package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.location.Location;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.provider.SyncStateContract;
import android.support.annotation.Nullable;
import android.util.Log;

import com.amap.api.maps.AMap;
import com.amap.api.maps.CameraUpdate;
import com.amap.api.maps.CameraUpdateFactory;
import com.amap.api.maps.CoordinateConverter;
import com.amap.api.maps.MapView;
import com.amap.api.maps.model.BitmapDescriptorFactory;
import com.amap.api.maps.model.CameraPosition;
import com.amap.api.maps.model.LatLng;
import com.amap.api.maps.model.MarkerOptions;
import com.amap.api.maps.model.MyLocationStyle;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;

/**
 * Created by lzxle on 2017/5/10.
 */

public class Map_Loc extends Activity implements AMap.OnMyLocationChangeListener {
    private MapView mMapView;
    private MyLocationStyle myLocationStyle;
    private AMap aMap;
    private LatLng latlng;
    private Double lat=0.0,lon=0.0;
    private Timer timer;
    private TimerTask mTimerTask;
    private Handler mHandler;
    private MarkerOptions markerOption;
    private int ship_id;
    private int count = 0;
    @Override

    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.map_loc);
        //获取地图控件引用
        mMapView = (MapView) findViewById(R.id.map);
        //在activity执行onCreate时执行mMapView.onCreate(savedInstanceState)，创建地图
        mMapView.onCreate(savedInstanceState);
        Bundle bundle = this.getIntent().getExtras();
        ship_id = bundle.getInt("ship_id");
        init();
        getLocation();
        startRefresh();
        if (aMap == null) {
            aMap = mMapView.getMap();
        }

        mHandler = new Handler(){
            @Override
            public void handleMessage(Message msg) {
                switch (msg.what) {
                    case 0:
                        if(count==0){
                            changeCamera(
                                    CameraUpdateFactory.newCameraPosition(new CameraPosition(
                                            latlng, 16, 0, 0)));
                        }
                        count++;
                        //updateTextView();
                        addMarkersToMap();
                        break;
                    default:
                        break;
                }
            }
        };

    }
    private void init() {
        if (aMap == null) {
            aMap = mMapView.getMap();
        }
    }

    private void startRefresh(){        //刷新页面任务

        timer = new Timer();
        mTimerTask = new TimerTask() {
            @Override
            public void run() {
                //getData();
                getLocation();
            }
        };
        timer.schedule(mTimerTask, 3000, 3000);
    }

    private void stopTimer(){
        if(timer != null){
            timer.cancel();
            timer = null;
        }
        if(mTimerTask != null){
            mTimerTask.cancel();
            mTimerTask = null;
        }
    }
    private void addMarkersToMap() {
        aMap.clear();
        markerOption = new MarkerOptions().icon(BitmapDescriptorFactory
                .defaultMarker(BitmapDescriptorFactory.HUE_AZURE))
                .position(latlng)
                .draggable(true)
                .title("经纬度:")
                .snippet(lon.toString()+","+lat.toString());
        aMap.addMarker(markerOption);
    }

    private void getLocation(){
        Thread t = new Thread(new Runnable() {
            @Override
            public void run() {
                String response = NetConnect.get("http://112.74.213.181:88/select_detailinfo.php?ship_id="+ship_id);
                try {
                    JSONArray jsonArray = new JSONArray(response);
                    JSONObject jsonObject = jsonArray.optJSONObject(0);
                    if(lat == jsonObject.optDouble("lat") && lon == jsonObject.optDouble("lon")) {}
                    else {
                        lon = jsonObject.optDouble("lon");
                        lat = jsonObject.optDouble("lat");
                        //地址转换为高德地图地址
                        latlng = Tools.LatLngconvert(new LatLng(lat,lon), CoordinateConverter.CoordType.GPS,Map_Loc.this);

                        Message message = Message.obtain(mHandler, 0);
                        mHandler.sendMessage(message);
                    }


                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });
        t.start();
    }

    private void setUpMap() {

        // 如果要设置定位的默认状态，可以在此处进行设置
        myLocationStyle = new MyLocationStyle();
        aMap.setMyLocationStyle(myLocationStyle);

        aMap.getUiSettings().setMyLocationButtonEnabled(true);// 设置默认定位按钮是否显示
        aMap.setMyLocationEnabled(true);// 设置为true表示显示定位层并可触发定位，false表示隐藏定位层并不可触发定位，默认是false

    }



    private void changeCamera(CameraUpdate update) {        //移动地图焦点
        aMap.moveCamera(update);
    }
    @Override
    protected void onDestroy() {
        super.onDestroy();
        mMapView.onDestroy();
        stopTimer();
    }

    @Override
    protected void onResume() {
        super.onResume();
        mMapView.onResume();
    }

    @Override
    protected void onPause() {
        super.onPause();
        mMapView.onPause();
        stopTimer();
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        mMapView.onSaveInstanceState(outState);
    }

    @Override
    public void onMyLocationChange(Location location) {
        if(location != null) {
            Log.e("amap", "onMyLocationChange 定位成功， lat: " + location.getLatitude() + " lon: " + location.getLongitude());
            Bundle bundle = location.getExtras();
            if(bundle != null) {
                int errorCode = bundle.getInt(MyLocationStyle.ERROR_CODE);
                String errorInfo = bundle.getString(MyLocationStyle.ERROR_INFO);
                // 定位类型，可能为GPS WIFI等，具体可以参考官网的定位SDK介绍
                int locationType = bundle.getInt(MyLocationStyle.LOCATION_TYPE);
                /*
                errorCode
                errorInfo
                locationType
                */
                Log.e("amap", "定位信息， code: " + errorCode + " errorInfo: " + errorInfo + " locationType: " + locationType );
            } else {
                Log.e("amap", "定位信息， bundle is null ");

            }

        } else {
            Log.e("amap", "定位失败");
        }
    }

}
