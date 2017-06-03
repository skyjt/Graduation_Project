package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Looper;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.Checkable;
import android.widget.CompoundButton;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

/**
 * Created by lzxle on 2017/3/27.
 */

public class LoginAcitivity extends Activity {
    private Button mLogin_btn;
    private TextView mUesrname,mPassword;
    private String username,password;
    private CheckBox mAutologin,mSavepass;
    private boolean autologin,savepass;
    private SharedPreferences sharedPreferences;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        initView();
        if(savepass) {
            mSavepass.setChecked(true);
            mPassword.setText(password);
        }
        else mSavepass.setChecked(false);

        if(autologin) {

            checkUser();
        }
        else mAutologin.setChecked(false);

        mAutologin.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                SharedPreferences.Editor editor = sharedPreferences.edit();
                if(mAutologin.isChecked()){
                    editor.putBoolean("autologin",true);
                    mSavepass.setChecked(true);
                    editor.putBoolean("savepass",true);
                }
                else {
                    editor.putBoolean("autologin",false);
                }
                editor.apply();
            }
        });


        mSavepass.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {         //自动保存密码按钮
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                SharedPreferences.Editor editor = sharedPreferences.edit();
                //System.out.print(savepass);
                if(mSavepass.isChecked()){
                    editor.putBoolean("savepass",true);     //如过被选中，将选中状态保存到sharedPreference中
                }
                else {
                    editor.putBoolean("savepass",false);
                    editor.putBoolean("autologin",false);
                    mAutologin.setChecked(false);
                }
                editor.apply();
            }
        });

        mLogin_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                checkUser();
            }
        });
    }

    private void initView() {
        mLogin_btn = (Button) findViewById(R.id.login_btn);             //初始化登录按钮
        mUesrname = (TextView) findViewById(R.id.username);             //初始化用户名输入框
        mPassword = (TextView) findViewById(R.id.password);             //初始化密码输入框
        mAutologin = (CheckBox) findViewById(R.id.autologin);           //初始化自动登录选项
        mSavepass = (CheckBox) findViewById(R.id.savepass);             //初始化保存密码选项
        sharedPreferences = getSharedPreferences("login_info",MODE_PRIVATE);    //初始化sharedPreferences
        username = sharedPreferences.getString("username","");      //获取之前保存的用户名
        password = sharedPreferences.getString("password","");      //获取之前保存的密码
        autologin = sharedPreferences.getBoolean("autologin",false);        //获取自动登录标识符，默认为false
        savepass = sharedPreferences.getBoolean("savepass",false);          //获取保存密码标识符，同上
        mUesrname.setText(username);      //将获取的用户名自动输入到用户名框
    }

    /**
     * 0 账号或密码为空
     * 1 账号或密码错误
     * 2 正确
     *
     */
    private void checkUser(){
        //final String[] result = new String[1];
        username = mUesrname.getText().toString().trim();
        password = mPassword.getText().toString();
        if("".equals(username)||"".equals(password)){       //判断账号密码是否为空
            Toast toast = Toast.makeText(getApplicationContext(),"账号或密码不得为空",Toast.LENGTH_SHORT);
        }
        else {
            Thread thread = new Thread(new Runnable(){
                @Override
                public void run() {
                    String result = NetConnect.checkpasswd(username,password);
                    if("true".equals(result.trim())){
                        SharedPreferences.Editor editor = sharedPreferences.edit();             //将正确的账号密码保存入sharedpreference以便下次调用
                        editor.putString("username",mUesrname.getText().toString().trim());
                        editor.putString("password",mPassword.getText().toString());
                        editor.apply();
                        Intent intent = new Intent(LoginAcitivity.this,ShipList.class);
                        startActivity(intent);
                        Looper.prepare();
                        Toast toast = Toast.makeText(getApplicationContext(), "登录成功",Toast.LENGTH_SHORT);
                        toast.show();
                        finish();
                        Looper.loop();

                    }
                    else {
                        mPassword.setText("");
                        Looper.prepare();
                        Toast toast = Toast.makeText(getApplicationContext(),"账号或密码错误",Toast.LENGTH_SHORT);
                        toast.show();
                        Looper.loop();
                    }
                }
            });
            thread.start();
        }

    }
}
