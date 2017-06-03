package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.unmanedship.lzxle.unmanned.Adapter.Text_bean;

import java.util.List;

public class MainActivity extends Activity {
    private Button button;
    private List<Text_bean> bean;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.test);
        button = (Button) findViewById(R.id.button);
        button.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View v) {
                Intent intent = new Intent(MainActivity.this,ShipList.class);
                startActivity(intent);
            }
        });
    }
}
