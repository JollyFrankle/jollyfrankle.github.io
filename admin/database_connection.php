<?php
try{
$connect= new PDO("mysql:host=localhost;dbname=u463413534_dho","u463413534_dho","GTK1958provNTT");
$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/css/bootstrap.min.css">
<title>Basis Data Sibuk</title>
<main class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
  <div class="container">
    <div class="card card-body text-center bg-danger text-white">
      <h1 class="display-3">Mohon maaf.</h1>
      <p class="lead mb-1">Basis data sedang sibuk dan tidak dapat melayani permintaan Anda saat ini.</p>
    </div>
  </div>
</main><?http_response_code(503);die();}
$GLOBALS['dbcon']=$connect;
//define("dbcon",$connect);
$title = '— SISTER v.Alpha 2101H1';
date_default_timezone_set('Asia/Makassar');
function bulan($c){
  if($c=='01')$o='Januari'; if($c=='02')$o='Februari'; if($c=='03')$o='Maret'; if($c=='04')$o='April'; if($c=='05')$o='Mei'; if($c=='06')$o='Juni'; if($c=='07')$o='Juli'; if($c=='08')$o='Agustus'; if($c=='09')$o='September'; if($c=='10')$o='Oktober'; if($c=='11')$o='November'; if($c=='12')$o='Desember'; return $o;
}
function arrslashes($v){return '"'.addslashes($v).'"';}
function dati2($c) {
  $pre='Kabupaten ';
  if($c=="06")$o=$pre.'Alor';
  if($c=="05")$o=$pre.'Belu';
  if($c=="09")$o=$pre.'Ende';
  if($c=="07")$o=$pre.'Flores Timur';
  if($c=="01")$o=$pre.'Kupang';
  if($c=="14")$o=$pre.'Lembata';
  if($c=="22")$o=$pre.'Malaka';
  if($c=="16")$o=$pre.'Manggarai Barat';
  if($c=="20")$o=$pre.'Manggarai Timur';
  if($c=="11")$o=$pre.'Manggarai';
  if($c=="17")$o=$pre.'Nagekeo';
  if($c=="10")$o=$pre.'Ngada';
  if($c=="15")$o=$pre.'Rote Ndao';
  if($c=="21")$o=$pre.'Sabu Raijua';
  if($c=="08")$o=$pre.'Sikka';
  if($c=="19")$o=$pre.'Sumba Barat Daya';
  if($c=="13")$o=$pre.'Sumba Barat';
  if($c=="18")$o=$pre.'Sumba Tengah';
  if($c=="12")$o=$pre.'Sumba Timur';
  if($c=="03")$o=$pre.'Timor Tengah Selatan';
  if($c=="04")$o=$pre.'Timor Tengah Utara';
  if($c=="60")$o='Kota Kupang';
  return $o;
}
function sql_dbop($c,$q,$d='') {
  $s=$c->prepare($q);
  if($d)$s->execute($d);else $s->execute();
}
function sql_value($c,$q,$d='') {
  $s=$GLOBALS['connect']->prepare($q);
  if($d)$s->execute($d);else $s->execute();
  return $s->fetchColumn();
}
function selisihTgl($date_1, $date_2, $format='%a') {
  $date1 = date_create($date_1); $date2 = date_create($date_2);
  $jarak = date_diff($date1, $date2);
  if($date1<$date2) return 0;
  else return $jarak->format($format);
}
function sql_valarray($c,$q,$col=0,$d=0) {
	$s=$c->prepare($q);
	if($d)$s->execute($d);else $s->execute();
	return $s->fetchAll(PDO::FETCH_COLUMN, $col);
}
function jlhkeh($c,$id,$aw,$ak,$st) {
  $q = "
  SELECT count(dfhd_id) FROM tbl_dft_hadir
  WHERE gtk_id=:id
  AND (dfhd_tgl BETWEEN :aw AND :ak) AND dfhd_status = :st
  ";
  $s=$c->prepare($q);
  $s->execute(array(":id"=>$id,":aw"=>$aw,":ak"=>$ak,":st"=>$st));
  return $s->fetchColumn();
}
function npsn_to_id($c,$n) {
  $s = $c->prepare("SELECT sek_id FROM tbl_sekolah WHERE sek_npsn=:npsn");
  $s->execute(array(':npsn'=>$n));
  return $s->fetchColumn();
}
function filter_xsshtml($x){return htmlspecialchars($x,ENT_NOQUOTES);}
$opt = array(
  "error" =>  true,
  "text"  =>  date("H:i:s").": Permintaan Anda tidak valid. Hubungi webmaster (<a href='mailto:sister@gtkprovntt.com' class='alert-link'>sister@gtkprovntt.com</a>)."
);
?>