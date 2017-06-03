package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.view.View;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.Switch;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import static com.unmanedship.lzxle.unmanned.ShipList._instance;

/**
 * Created by lzxle on 2017/5/11.
 *
 */

public class Setting extends Activity {
    private Switch mAutoLogin;
    private Switch mTelNotify;
    private EditText mTel;
    private Button mExitButton;
    private boolean autologin_check,tel_check;
    private String tel,username;
    private boolean savepass_check;
    private SharedPreferences sharedPreferences;
    private Handler mHandler;
    private JSONArray jsonArray;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.setting);
        init();
        mAutoLogin.setChecked(autologin_check);
        //mTelNotify.setChecked(tel_check);
        //mTel.setText(tel);
        if(!savepass_check){
            mAutoLogin.setEnabled(false);
        }
        checkNofity();
        mAutoLogin.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                SharedPreferences.Editor editor = sharedPreferences.edit();
                if (isChecked){
                    editor.putBoolean("autologin",true);        //打开自动登录功能
                }else {
                    editor.putBoolean("autologin",false);       //关闭自动登录功能
                }
                editor.apply();
            }
        });

        mTelNotify.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                final int val = isChecked? 1 : 0;

                Thread Tel_Update = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        String response = NetConnect.get("http://112.74.213.181:88/update_tel.php?username="+username+"&tel="+mTel.getText().toString()+"&notify="+val);
                    }
                });
                Tel_Update.start();

            }
        });
        mExitButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                SharedPreferences.Editor editor = sharedPreferences.edit();
                editor.putString("password","");
                editor.putBoolean("autologin",false);
                editor.putBoolean("savepass",false);
                editor.apply();
                Intent intent = new Intent(Setting.this,LoginAcitivity.class);
                startActivity(intent);
                _instance.finish();
                finish();
            }
        });
        mHandler = new Handler(){
            @Override
            public void handleMessage(Message msg){
                switch (msg.what){
                    case 0:
                        mTelNotify.setChecked(false);
                        mTel.setText(tel);
                        break;
                    case 1:
                        mTelNotify.setChecked(true);
                        mTel.setText(tel);
                        break;
                    default:
                        break;
                }
            }
        };

    }

    private void init(){
        mAutoLogin = (Switch) findViewById(R.id.auto_login_setting);
        mTelNotify = (Switch) findViewById(R.id.tel_notify);
        mTel = (EditText) findViewById(R.id.email);
        mExitButton = (Button) findViewById(R.id.exit);
        sharedPreferences = getSharedPreferences("login_info",MODE_PRIVATE);    //初始化sharedPreferences
        autologin_check = sharedPreferences.getBoolean("autologin",false);            //获取自动登录标识符，默认为false

        savepass_check = sharedPreferences.getBoolean("savepass",false);
        username = sharedPreferences.getString("username","");
    }

    private void checkNofity(){
        Thread ck = new Thread(new Runnable() {
            @Override
            public void run() {
                String response = NetConnect.get("http://112.74.213.181:88/get_notify_flag.php?username="+username);
                try {
                    jsonArray = new JSONArray(response.trim());
                    JSONObject jsonObject = jsonArray.optJSONObject(0);
                    tel = jsonObject.optString("tel");
                    int handlermsg = jsonObject.optInt("notify");
                    Message message = Message.obtain(mHandler,handlermsg);
                    mHandler.sendMessage(message);
                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        });
        ck.start();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Thread Tel_Update = new Thread(new Runnable() {
            @Override
            public void run() {
                String response = NetConnect.get("http://112.74.213.181:88/update_tel.php?username="+username+"&tel="+mTel.getText().toString());
            }
        });
        Tel_Update.start();
    }
}
