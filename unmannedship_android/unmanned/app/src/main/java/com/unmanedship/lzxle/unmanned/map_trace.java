package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;
import android.support.annotation.Nullable;
import android.view.inputmethod.EditorInfo;
import android.widget.EditText;
import android.widget.Toast;


import com.amap.api.maps.AMap;
import com.amap.api.maps.CameraUpdate;
import com.amap.api.maps.CameraUpdateFactory;
import com.amap.api.maps.CoordinateConverter;
import com.amap.api.maps.MapView;
import com.amap.api.maps.model.BitmapDescriptorFactory;
import com.amap.api.maps.model.CameraPosition;
import com.amap.api.maps.model.LatLng;
import com.amap.api.maps.model.MarkerOptions;
import com.amap.api.maps.model.Polyline;
import com.amap.api.maps.model.PolylineOptions;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;


/**
 * Created by lzxle on 2017/4/6.
 */

public class map_trace extends Activity {
    private MapView mapView;
    private AMap aMap;
    private int ship_id,sum;
    private Polyline polyline;
    private Handler mHandler;
    private List<LatLng> latLngs = new ArrayList<LatLng>();
    private List<MarkerOptions> markerOptionses = new ArrayList<MarkerOptions>();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.maptra);
        mapView = (MapView)findViewById(R.id.map);
        mapView.onCreate(savedInstanceState);
        Bundle bundle = this.getIntent().getExtras();
        ship_id = bundle.getInt("ship_id");

        init();
        history_sum();
        mHandler = new Handler(){
            @Override
            public void handleMessage(Message msg) {
                switch (msg.what){
                    case 0:
                        //添加连线
                        polyline =aMap.addPolyline((new PolylineOptions()).
                                addAll(latLngs).width(6).color(Color.argb(255, 0, 139, 237)));
                        LatLng latLng = latLngs.get(0);
                        //changeCamera(CameraUpdateFactory.newCameraPosition(new CameraPosition(latLng,16,0,0)));
                        aMap.addMarkers((ArrayList<MarkerOptions>) markerOptionses,true);
                        break;
                    default:
                        break;
                }
            }
        };

    }

    private void init() {
        if(aMap == null){
            aMap = mapView.getMap();
        }
    }

    private void changeCamera(CameraUpdate update){
        aMap.moveCamera(update);
    }

    private void history_sum(){
        final EditText et = new EditText(this);
        et.setInputType(EditorInfo.TYPE_CLASS_NUMBER);
        new AlertDialog.Builder(this).setTitle("请输入查寻条数")
                .setIcon(android.R.drawable.ic_dialog_info)
                .setView(et)
                .setPositiveButton("确定", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        sum = Integer.parseInt(et.getText().toString());
                        getHisMark(ship_id,sum);
                    }
                })
                .setNegativeButton("取消", null)
                .show();
    }

    private void getHisMark(final int id, final int sum){
        final Thread t = new Thread(new Runnable() {
            @Override
            public void run() {
                String response = NetConnect.get("http://112.74.213.181:88/select_loc.php?id="+id+"&sum="+sum);
                try {
                    JSONArray jsonArray = new JSONArray(response);
                    for (int i = 0; i < jsonArray.length(); i++){
                        JSONObject jsonObject = jsonArray.optJSONObject(i);
                        //将获取的坐标转换为高的坐标
                        latLngs.add(Tools.LatLngconvert(new LatLng(jsonObject.optDouble("lat"),jsonObject.optDouble("lon")), CoordinateConverter.CoordType.GPS,map_trace.this));
                        markerOptionses.add(new MarkerOptions()
                                .icon(BitmapDescriptorFactory
                                .defaultMarker(BitmapDescriptorFactory.HUE_AZURE))
                                .position(Tools.LatLngconvert(new LatLng(jsonObject.optDouble("lat"),jsonObject.optDouble("lon")), CoordinateConverter.CoordType.GPS,map_trace.this))
                                .draggable(true)
                                .title("经纬度,时间：")
                                .snippet(jsonObject.optDouble("lon")+","+jsonObject.optDouble("lat")+"\n"+jsonObject.optString("time")));
                    }
                    Message message = Message.obtain(mHandler, 0);
                    mHandler.sendMessage(message);

                } catch (JSONException e) {
                    Looper.prepare();
                    Toast toast = Toast.makeText(getApplicationContext(),"无数据",Toast.LENGTH_SHORT);
                    toast.show();
                    e.printStackTrace();
                    Looper.loop();
                }
            }
        });
        t.start();
    }

    @Override
    protected void onResume() {
        super.onResume();
        mapView.onResume();
    }

    @Override
    protected void onPause() {
        super.onPause();
        mapView.onPause();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        mapView.onDestroy();
    }
}
