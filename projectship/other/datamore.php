<?php 
    
    
	set_time_limit(0);

	/*ģ��Ѳ�ߴ����������ݿ�������� */

	$conn = mysql_connect('localhost','root','root');
    mysql_query('use patrolShip',$conn);
    mysql_query('set names utf8',$conn);

	$time=time();

/*

//�¶ȷ�Χ -10 ��40
//�ܽ�����Χ 0-50mg/l��


//��ʾ��
120.790, 31.979
*/

//�������� 50 ������

for($i=0;$i<50;$i++){
$shipId=mt_rand(1,3);
$jingdu=mt_rand(120790,120870)/1000;
$weidu=mt_rand(31879,32079)/1000;

$wendu=mt_rand(-10,40);
$rongjieyang=mt_rand(0,50);

$shijian=mt_rand($time-24*60*60,$time);
$shijian=date("20y-m-d H:i:s",$shijian);
	
   echo '<hr/>';

   $sql="insert into msg(shipId,jingdu,weidu,wendu,rongjieyang,shijian) values('$shipId',$jingdu,$weidu,$wendu,$rongjieyang,'$shijian')";
   
   echo $sql;

	$res=mysql_query($sql);
   sleep(1);   
}