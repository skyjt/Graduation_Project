package com.unmanedship.lzxle.unmanned.Adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import com.unmanedship.lzxle.unmanned.R;

import java.util.HashMap;
import java.util.List;
import java.util.zip.Inflater;

/**
 * Created by lzxle on 2017/4/4.
 */

public class MyBaseAdapter extends BaseAdapter {
    private int[] colors = new int[] {0xffffff};
    private List<Text_bean> DataList;
    private Context mContext;
    private LayoutInflater mInflater;
    //private List<HashMap<String, Object>> dataList;
    private ViewHolder holder = null;

    public MyBaseAdapter(Context context,List<Text_bean> dataList){
        this.mContext = context;
        this.DataList = dataList;
        mInflater = LayoutInflater.from(context);
    }
    @Override
    public int getCount() {
        return DataList.size();
    }

    @Override
    public Text_bean getItem(int position) {
        return DataList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if(convertView == null) {
            holder = new ViewHolder();
            convertView = mInflater.inflate(R.layout.shiplist_detail,parent,false);
            holder.name = (TextView) convertView.findViewById(R.id.ship_name);
            holder.status = (TextView) convertView.findViewById(R.id.ship_status);
            holder.lastplace = (TextView) convertView.findViewById(R.id.last_place);
            holder.lasttime = (TextView) convertView.findViewById(R.id.last_time);
            convertView.setTag(holder);
        }else {
            holder = (ViewHolder) convertView.getTag();
        }
        Text_bean bean = DataList.get(position);

        holder.name.setText(bean.getName());
        holder.status.setText(bean.getStatus());
        //holder.status.setTextColor(0xA4E2C6);
        //if(bean.getStatus().equals("在线")) holder.status.setTextColor(0xA4E2C6);
        //else holder.status.setTextColor(0x000000);
        holder.lasttime.setText(bean.getLasttime());
        holder.lastplace.setText(bean.getLastplace());
        return convertView;
    }

    private class ViewHolder{
        TextView name;
        TextView status;
        TextView lasttime;
        TextView lastplace;
    }
}
