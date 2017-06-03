package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.app.DatePickerDialog;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.TextView;
import android.widget.TimePicker;

import java.util.Calendar;

/**
 * Created by lzxle on 2017/5/19.
 */

public class Time_pick extends Activity {
    private Button mSearch;
    private TextView mFrom_time,mEnd_time;
    private String From_time,End_time,res;
    private int ship_id;
    private Handler mHandler;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.time_choose);
        init();     //初始化
        mFrom_time.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showFromDialogPick((TextView) v);
            }
        });
        mEnd_time.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                showEndDialogPick((TextView) v);
            }
        });

        mSearch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                final String url = "http://112.74.213.181:88/chartinfo.php?shipId="+ship_id+"&shijian1="+mFrom_time.getText().toString()+"&shijian2="+mEnd_time.getText().toString();
                Thread getData = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        res = NetConnect.get(url);
                        Message message = Message.obtain(mHandler,0);
                        mHandler.sendMessage(message);
                    }
                });
                getData.start();

            }
        });
        mHandler = new Handler(){
            @Override
            public void handleMessage(Message msg){
                switch (msg.what){
                    case 0:
                        Intent intent = new Intent(Time_pick.this,Info_List.class);
                        Bundle bundle = new Bundle();
                        bundle.putString("res",res);
                        intent.putExtras(bundle);
                        startActivity(intent);
                        break;
                    default:
                        break;
                }
            }
        };
    }

    private void init(){        //加载各种视图，初始数据等
        mSearch = (Button) findViewById(R.id.search);
        mFrom_time = (TextView) findViewById(R.id.from_time);
        mEnd_time = (TextView) findViewById(R.id.end_time);
        Bundle bundle = this.getIntent().getExtras();
        ship_id = bundle.getInt("ship_id");
    }

    private void showFromDialogPick(final TextView timeText) {
        final StringBuffer time = new StringBuffer();
        //获取Calendar对象，用于获取当前时间
        final Calendar calendar = Calendar.getInstance();
        int year = calendar.get(Calendar.YEAR);
        int month = calendar.get(Calendar.MONTH);
        int day = calendar.get(Calendar.DAY_OF_MONTH);
        int hour = calendar.get(Calendar.HOUR_OF_DAY);
        int minute = calendar.get(Calendar.MINUTE);
        //实例化TimePickerDialog对象
        final TimePickerDialog timePickerDialog = new TimePickerDialog(Time_pick.this, new TimePickerDialog.OnTimeSetListener() {
            //选择完时间后会调用该回调函数
            @Override
            public void onTimeSet(TimePicker view, int hourOfDay, int minute) {
                if(hourOfDay == 0){
                    time.append(" "  + "00" + ":" + minute);
                }
                else if(minute == 0){
                    time.append(" "  + hourOfDay + ":" + "00");

                }else {
                    time.append(" "  + hourOfDay + ":" + minute);
                }
                //设置TextView显示最终选择的时间
                timeText.setText(time);
                From_time = time.toString();
                Log.i("fromtime",From_time);
            }
        }, hour, minute, true);
        //实例化DatePickerDialog对象
        DatePickerDialog datePickerDialog = new DatePickerDialog(Time_pick.this, new DatePickerDialog.OnDateSetListener() {
            //选择完日期后会调用该回调函数
            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
                //因为monthOfYear会比实际月份少一月所以这边要加1
                time.append(year + "-" + (monthOfYear+1) + "-" + dayOfMonth);
                //选择完日期后弹出选择时间对话框
                timePickerDialog.show();
            }
        }, year, month, day);
        //弹出选择日期对话框
        datePickerDialog.show();
    }
    private void showEndDialogPick(final TextView timeText) {
        final StringBuffer time = new StringBuffer();
        //获取Calendar对象，用于获取当前时间
        final Calendar calendar = Calendar.getInstance();
        int year = calendar.get(Calendar.YEAR);
        int month = calendar.get(Calendar.MONTH);
        int day = calendar.get(Calendar.DAY_OF_MONTH);
        int hour = calendar.get(Calendar.HOUR_OF_DAY);
        int minute = calendar.get(Calendar.MINUTE);
        //实例化TimePickerDialog对象
        final TimePickerDialog timePickerDialog = new TimePickerDialog(Time_pick.this, new TimePickerDialog.OnTimeSetListener() {
            //选择完时间后会调用该回调函数
            @Override
            public void onTimeSet(TimePicker view, int hourOfDay, int minute) {
                if(hourOfDay == 0){
                    time.append(" "  + "00" + ":" + minute);
                }
                else if(minute == 0){
                    time.append(" "  + hourOfDay + ":" + "00");

                }else {
                    time.append(" "  + hourOfDay + ":" + minute);
                }                //设置TextView显示最终选择的时间
                timeText.setText(time);
                End_time = time.toString();
                mSearch.setEnabled(true);
                Log.i("endtime",End_time);
            }
        }, hour, minute, true);
        //实例化DatePickerDialog对象
        DatePickerDialog datePickerDialog = new DatePickerDialog(Time_pick.this, new DatePickerDialog.OnDateSetListener() {
            //选择完日期后会调用该回调函数
            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
                //因为monthOfYear会比实际月份少一月所以这边要加1
                time.append(year + "-" + (monthOfYear+1) + "-" + dayOfMonth);
                //选择完日期后弹出选择时间对话框
                timePickerDialog.show();
            }
        }, year, month, day);
        //弹出选择日期对话框
        datePickerDialog.show();
    }
}
