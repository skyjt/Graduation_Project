package com.unmanedship.lzxle.unmanned;

import android.content.Context;

import com.amap.api.maps.CoordinateConverter;
import com.amap.api.maps.model.LatLng;
import com.amap.api.services.core.LatLonPoint;

/**
 * Created by lzxle on 2017/5/14.
 */

public class Tools {
    /**
     * 根据类型 转换 坐标
     */
    public static LatLonPoint LatLonPointconvert(LatLonPoint sourceLatLng, CoordinateConverter.CoordType coord, Context context) {
        LatLng source = new LatLng(sourceLatLng.getLatitude(),sourceLatLng.getLongitude());
        CoordinateConverter converter  = new CoordinateConverter(context);
        // CoordType.GPS 待转换坐标类型
        converter.from(coord);
        // sourceLatLng待转换坐标点
        converter.coord(source);
        // 执行转换操作
        LatLng desLatLng = converter.convert();
        LatLonPoint desLatLonPoint = new LatLonPoint(desLatLng.latitude,desLatLng.longitude);
        return desLatLonPoint;
    }

    public static LatLng LatLngconvert(LatLng sourceLatLng, CoordinateConverter.CoordType coord, Context context) {
        CoordinateConverter converter  = new CoordinateConverter(context);
        // CoordType.GPS 待转换坐标类型
        converter.from(coord);
        // sourceLatLng待转换坐标点
        converter.coord(sourceLatLng);
        // 执行转换操作
        LatLng desLatLng = converter.convert();
        return desLatLng;
    }


}
