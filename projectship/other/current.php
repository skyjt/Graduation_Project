<?php 
    


	/*���� ģ��Ѳ�ߴ� ʵʱ�����ݿⷢ������*/

	set_time_limit(0);

	$conn = mysql_connect('localhost','root','root');
    mysql_query('use patrolShip',$conn);
    mysql_query('set names utf8',$conn);

	

	

/*

//�¶ȷ�Χ -10 ��40              ���� ������ 0-30 ������
//�ܽ�����Χ 0-50mg/l��          ���� ������10-40 ������


//��ʾ��
120.790, 31.979
*/

//ѭ��8 �Σ�ÿ��3��ʵʱ����һ��������

for($i=0;$i<8;$i++){

$shipId=mt_rand(1,3);
$jingdu=mt_rand(1209170,1209172)/10000;
$weidu=mt_rand(319770,319780)/10000;

$wendu=mt_rand(-10,40);
$rongjieyang=mt_rand(0,50);

$shijian=date("20y-m-d H:i:s");
	
   echo '<hr/>';

   $sql="insert into msg(shipId,jingdu,weidu,wendu,rongjieyang,shijian) values('$shipId',$jingdu,$weidu,$wendu,$rongjieyang,'$shijian')";
   
   echo $sql;

	$res=mysql_query($sql);
   sleep(3);   
}