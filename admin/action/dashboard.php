<?include'../database_connection.php';
session_start();

if(!isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
  // CURDATE() bisa diubah ke date php karena curdate itu gunakan UTC+0, jadi update setiap jam 8 pagi
  $query="
    SELECT
      YEAR(CURDATE()) - YEAR(JSON_UNQUOTE(JSON_EXTRACT(gtk_ttl, '$[1]'))) - IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), '-', MONTH(JSON_UNQUOTE(JSON_EXTRACT(gtk_ttl, '$[1]'))), '-', DAY(JSON_UNQUOTE(JSON_EXTRACT(gtk_ttl, '$[1]')))) ,'%Y-%c-%e') > CURDATE(), 1, 0) AS USIA,
      COUNT(gtk_id) AS JLH,
      CAST(SUM(IF(statpeg = 'PNS', 1, 0)) AS SIGNED) AS SP_PNS,
      CAST(SUM(IF(statpeg!= 'PNS', 1, 0)) AS SIGNED) AS SP_NPNS,
      CAST(SUM(IF(gtk_jk = 'L',1,0)) AS SIGNED) AS JK_L,
      CAST(SUM(IF(gtk_jk = 'P',1,0)) AS SIGNED) AS JK_P
    FROM tbl_gtk
    GROUP BY USIA
  ";
  /*if($_SESSION["adm_level"]==2) $query.="
    WHERE 
  ";*/
  
  $st = $connect->prepare($query);
  //if($_SESSION["adm_level"]==2) $st->execute($data); else 
  $st->execute();
  $res = $st->fetchAll(PDO::FETCH_ASSOC);
  foreach($res as $r){
    $JLH[$r["USIA"]]=$r["JLH"];
    $SP_PNS[$r["USIA"]]=$r["SP_PNS"];
    $SP_NPNS[$r["USIA"]]=$r["SP_NPNS"];
    $JK_L[$r["USIA"]]=$r["JK_L"];
    $JK_P[$r["USIA"]]=$r["JK_P"];
  }
  for($i=20;$i<=60;$i++){
    //if(!$JLH[$i])$JLH[$i]=0;
    $usia[]=$i;
    $c_JLH[]=$JLH[$i];
    $c_SP_PNS[]=$SP_PNS[$i];
    $c_SP_NPNS[]=$SP_NPNS[$i];
    $c_JK_L[]=$JK_L[$i];
    $c_JK_P[]=$JK_P[$i];
  }
  $opt = array(
    'usia_rentang'  =>  json_encode($usia),
    'usia_jumlah'   =>  json_encode($c_JLH),
    'SP_PNS'        =>  json_encode($c_SP_PNS),
    'SP_NPNS'       =>  json_encode($c_SP_NPNS),
    'JK_L'          =>  json_encode($c_JK_L),
    'JK_P'          =>  json_encode($c_JK_P),
  );
  echo json_encode($opt);
}