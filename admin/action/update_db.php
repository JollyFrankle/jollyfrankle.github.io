<?include'../database_connection.php';
/*set_time_limit(600);
if($_GET["mode"]=='q1')
$q="SELECT dt_npsn FROM tbl_sekdb WHERE LEFT(dt_lastupdate,10)!='".date("Y-m-d")."' AND (dt_id % 4) = 0";
elseif($_GET["mode"]=='q2')
$q="SELECT dt_npsn FROM tbl_sekdb WHERE LEFT(dt_lastupdate,10)!='".date("Y-m-d")."' AND (dt_id % 4) = 1";
elseif($_GET["mode"]=='q3')
$q="SELECT dt_npsn FROM tbl_sekdb WHERE LEFT(dt_lastupdate,10)!='".date("Y-m-d")."' AND (dt_id % 4) = 2";
elseif($_GET["mode"]=='q4')
$q="SELECT dt_npsn FROM tbl_sekdb WHERE LEFT(dt_lastupdate,10)!='".date("Y-m-d")."' AND (dt_id % 4) = 3";
elseif($_GET["mode"]=='all' && $_GET["page"])
$q="SELECT dt_npsn FROM tbl_sekdb LIMIT ".(($_GET["page"]-1)*50).", 50";
elseif($_GET["npsn"])
$q="SELECT dt_npsn FROM tbl_sekdb WHERE dt_npsn='".$_GET["npsn"]."'";
elseif($_GET["idstart"] && $_GET["idend"])
$q="SELECT dt_npsn FROM tbl_sekdb WHERE (dt_id BETWEEN '".$_GET["idstart"]."' AND '".$_GET["idend"]."')";
elseif(($_GET["empty"]=='dt_lastupdate' || $_GET["empty"]=='dt_nama' || $_GET["empty"]=='dt_status') && $_GET["page"])
$q="SELECT dt_npsn FROM tbl_sekdb WHERE ".$_GET["empty"]." IS NULL LIMIT ".(($_GET["page"]-1)*50).", 50";
else die('Parameter belum ditentukan: page, npsn, idstart-idend, empty-page.');

$st=$connect->prepare($q);
$st->execute();
$res = $st->fetchAll();
foreach($res as $r)
{
    $dpd = json_decode(file_get_contents("https://dapo.kemdikbud.go.id/api/getHasilPencarian?keyword=".$r["dt_npsn"]),true);
	if(isset($dpd[0])) {
    	$query = "
    	UPDATE tbl_sekdb 
    	SET dt_status = '".$dpd[0]["status"]."',
    	dt_dati3kode = '".$dpd[0]["kode_kecamatan"]."',
    	dt_dapodikid = '".$dpd[0]["sekolah_id_enkrip"]."',
    	dt_nama = '".$dpd[0]["nama_sekolah"]."',
    	dt_dati3 = '".str_replace('Kec. ','',$dpd[0]["kecamatan"])."',
    	dt_lastupdate = '".date("Y-m-d H:i:s")."'
    	WHERE dt_npsn = '".$r["dt_npsn"]."'
    	";
    	$statement = $connect->prepare($query);
    	if($statement->execute()) {
    	    $data.=($no+1).'. '.$r["dt_npsn"].'_OK\n';
    	}
	} else $data.=($no+1).'. <b style="color:red">'.$r["dt_npsn"].'_ERROR</b>\n';
	$no++;
}
echo $data;*/
$q="SELECT gtk_id,gtk_jenis,mapel,gtk_kelas,gtk_tmt_kepsek FROM tbl_gtk";
$st=$connect->prepare($q);
$st->execute();
$res = $st->fetchAll();
foreach($res as $r) {
  if(!json_decode($r["gtk_jenis"])) {
    if($r["gtk_jenis"]=="Kepsek") {
      $data=array(
        ":jenis"  =>  json_encode(array($r["gtk_jenis"],$r["gtk_tmt_kepsek"])),
        ":id"     =>  $r["gtk_id"]
        );
    }else {
      $jenis=array();
      $jenis[]=$r["gtk_jenis"];
      if($r["mapel"])$jenis[]=$r["mapel"];
      if($r["gtk_kelas"])$jenis[]=$r["gtk_kelas"];
      $data=array(
        ":jenis"  =>  json_encode($jenis),
        ":id"     =>  $r["gtk_id"]
      );
    }
    $q2="UPDATE tbl_gtk SET gtk_jenis=:jenis WHERE gtk_id=:id";
    $st=$connect->prepare($q2);
    $st->execute($data);
  }
}
echo 'aa';
echo json_decode('Aaa',true)[0];