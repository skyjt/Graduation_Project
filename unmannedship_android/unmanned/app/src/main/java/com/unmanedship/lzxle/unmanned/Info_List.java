package com.unmanedship.lzxle.unmanned;

import android.app.Activity;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;

import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.AxisBase;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;
import com.github.mikephil.charting.formatter.IAxisValueFormatter;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by lzxle on 2017/5/19.
 */

public class Info_List extends Activity {
    private LineChart lineChart_oxy,lineChart_temp;
    private String res;
    private List<Entry> Temp_Info = new ArrayList<>();
    private List<Entry> Oxy_Info = new ArrayList<>();
    private List<String> Time_XVals = new ArrayList<>();

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.infolist);
        lineChart_temp = (LineChart) findViewById(R.id.chart_temp);
        lineChart_oxy = (LineChart) findViewById(R.id.chart_oxy);
        Bundle bundle = this.getIntent().getExtras();
        res = bundle.getString("res");

        lineChart_temp.setData(getLineData(res,lineChart_temp,"temp","温度(单位:摄氏度)"));
        lineChart_oxy.setData(getLineData(res,lineChart_oxy,"oxy","溶解氧(单位：mg/L)"));
        lineChart_temp.invalidate();
        lineChart_oxy.invalidate();
    }

    /**
     *
     * @param res json解析数据
     * @param lineChart 图表参数
     * @param sort  所需解析数据类型
     * @param dataname  数据命名
     * @return  LineData
     */
    private LineData getLineData(String res, LineChart lineChart,String sort,String dataname){
        // LineDataSet(List<Entry> 資料點集合, String 類別名稱)
        final String[] quarters = getxAxis(res);
        IAxisValueFormatter formatter = new IAxisValueFormatter() {
            @Override
            public String getFormattedValue(float value, AxisBase axis) {
                return quarters[(int) value];
            }
        };

        LineDataSet dataSet = new LineDataSet(getChartData(res, sort), dataname);
        dataSet.setDrawCircles(false);
        dataSet.setLineWidth(3f);

        XAxis xAxis = lineChart.getXAxis();
        xAxis.setPosition(XAxis.XAxisPosition.BOTTOM);
        xAxis.setLabelCount(3,true);
        xAxis.setTextSize(10f);
        xAxis.setTextColor(Color.RED);
        xAxis.setDrawAxisLine(true);
        xAxis.setDrawGridLines(false);
        xAxis.setGranularity(1f); // minimum axis-step (interval) is 1
        xAxis.setValueFormatter(formatter);
        LineData data = new LineData(dataSet);
        return data;
    }

    /**
     *
     * @param res  json解析数据
     * @param sort  所需解析数据类型
     * @return  List<Entry>
     */
    private List<Entry> getChartData(String res, String sort){
        List<Entry> chartData = new ArrayList<>();
        try {
            JSONArray jsonArray = new JSONArray(res);
            for (int i = 0; i < jsonArray.length(); i++){
                JSONObject jsonObject = jsonArray.optJSONObject(i);
                chartData.add(new Entry(i,(float) jsonObject.optDouble(sort)));
            }
            return chartData;
        }catch (Exception e){
            e.printStackTrace();
            return null;
        }
    }

    private String[] getxAxis(String res){
        try {
            JSONArray jsonArray = new JSONArray(res);
            String[] datexAxis = new String[jsonArray.length()];
            for (int i = 0; i < jsonArray.length(); i++){
                JSONObject jsonObject = jsonArray.optJSONObject(i);
                datexAxis[i] = jsonObject.optString("datetime");
            }
           return datexAxis;
        }catch (Exception e){
            e.printStackTrace();
            return null;
        }
    }
}
