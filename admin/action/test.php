<?php include'../database_connection.php';
 /*
echo '<h1>Uji Coba Kode</h1>'.'<p>Pengaturan: <b>50x uji coba, masing-masing 10.000 iterasi</b></p>';
$query=array("11","123","123213","543","aew",'qewe""""""""""""""""""""');
function arrslashes($v){return addslashes($v);}
$x=0;
while ($x<50) {
$start=microtime(true); $i=0;
while ($i<10000) {
date("Y-m-d H:i:s");
implode(',', array_map('arrslashes', $query));
$i++;
} echo number_format(microtime(true)-$start, 10,',','.')."<br>"; $x++; }*/
/*
$query=sql_valarray($connect,"SELECT DISTINCT(gtk_id) FROM tbl_dft_hadir WHERE (dfhd_tgl BETWEEN '2020-10-01' AND '2020-10-31') AND dfhd_npsn = '50301404'",0);
echo implode(',', array_map('intval', $query));
$q2=array("AAA",1,2,3);
echo implode(',', array_map('arrslashes', $q2));*/
/*$array1=json_decode('[4,3,5,6,83,23,19,20,58,34,18,24,40,53,54,50,51,21,59,64,75,80,17,86,88,1]',true);
$array2=json_decode('[4,3,5,6,17,18,19,20,21,40,23,24,34,53,54,50,51,58,59,64,75,80,83,86,88,1]',true);
$array3=json_decode('["4","3","5","6","83","23","1","19","20","58","34","18","24","40","53","54","50","51","21","59","64","75","80","17","86","88"]',true);
$array3=array_map('intval', $array3);
sort($array1); sort($array2); sort($array3);
if($array1!==$array3) echo "no"; else echo "yes";*/
$array1=array("<tdo>qwe</td>",array("<tr>Ayyoh</tr>","<b>Lalalala</b>"));//json_decode('["<tdo>qwe</td>",["<tr>Ayyoh</tr>","<b>Lalalala</b>"],5,6,83,23,19,20,58,34,18,24,40,53,54,50,51,21,59,64,75,80,17,86,88,1]',true);
function htmlsc($x) {return htmlspecialchars($x, ENT_SUBSTITUTE);}
$array1c=array_map("htmlspecialchars",$array1);
echo $array1c[0].'<br/>'.htmlspecialchars($array1[0]);
//print_r($array1c);