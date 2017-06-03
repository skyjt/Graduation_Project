package com.unmanedship.lzxle.unmanned.Adapter;

import java.util.ArrayList;

/**
 * Created by lzxle on 2017/4/4.
 */

public class Text_bean {
    private String name,status,lasttime,lastplace;

    public Text_bean(String name, String status, String lasttime, String lastplace){
        this.name = name;
        this.status = status;
        this.lasttime = lasttime;
        this.lastplace = lastplace;
    }

    public String getName() {
        return name;
    }

    public String getStatus() {
        return status;
    }

    public String getLasttime() {
        return lasttime;
    }

    public String getLastplace() {
        return lastplace;
    }
}
