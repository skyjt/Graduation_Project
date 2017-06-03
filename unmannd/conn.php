<?php


$conn = mysql_connect('112.74.213.181','unman','unman123');

mysql_query('use unman',$conn);
mysql_query('set names utf8',$conn);



/*
 CREATE TABLE `msg` (
  `id` int primary key AUTO_INCREMENT,
  `shipId` int not null default 0,
  `wendu` int not null default 0,
  `rongjieyang` int not null default 0,
  `jingdu` float not null DEFAULT 0,
  `weidu` float not null DEFAULT 0,
  `shijian` datetime
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




CREATE TABLE `user` (
 `id` int(10)  PRIMARY KEY AUTO_INCREMENT,
 `username` char(10)  NOT NULL DEFAULT '',
 `password` char(30)  NOT NULL DEFAULT '',
 `roles` char(15)  DEFAULT '',
 `telephone` char(15)  DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;




巡航船表

CREATE TABLE `ship` (
 `id` int(10)  PRIMARY KEY AUTO_INCREMENT,
 `shipName` char(15)  NOT NULL DEFAULT '',
 `person` char(10)  NOT NULL DEFAULT '',
 `telephone` char(15)  DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

*/


