<? include('../database_connection.php');
session_start();
if(!isset($_POST) || !isset($_SESSION["adm_id"]) || !($_SESSION["adm_id"]==0 || $_SESSION["adm_id"]==1)){
include('../../403.html');
http_response_code(403);
} else {
  $opt=array("success"=>true);
  function boolchange($x){
    if($x==1)return'diaktifkan';
    if($x==0)return'dinonaktifkan';
  }
  if(isset($_POST["maintenance"])) {
    $data=array(":val1"=>$_POST["maintenance"]);
    sql_dbop($connect,"UPDATE options SET val1=:val1 WHERE set_var='maintenance'",$data);
    $opt["text"]=date("H:i:s")." Mode perawatan telah <b>".boolchange($_POST["maintenance"])."</b>.";
  }
  if(isset($_POST["pengst"])) {
    $data=array(":val1"=>$_POST["pengst"]);
    sql_dbop($connect,"UPDATE options SET val1=:val1 WHERE set_var='pengumuman'",$data);
    $opt["text"]=date("H:i:s")." Notifikasi/pengumuman di dashboard sekolah telah <b> ".boolchange($_POST["pengst"])."</b>.";
  }
  if(isset($_POST["regisakun"])) {
    $data=array(":val1"=>$_POST["regisakun"]);
    sql_dbop($connect,"UPDATE options SET val1=:val1 WHERE set_var='registrasi'",$data);
    $opt["text"]=date("H:i:s")." Pendaftaran akun sekolah baru telah <b>".boolchange($_POST["regisakun"])."</b>.";
  }
  if(isset($_POST["unggahberkas"])) {
    $data=array(":val1"=>$_POST["unggahberkas"]);
    sql_dbop($connect,"UPDATE options SET val1=:val1 WHERE set_var='unggah_berkas'",$data);
    $opt["text"]=date("H:i:s")." Pengunggahan berkas telah <b>".boolchange($_POST["unggahberkas"])."</b>.";
  }
  if(isset($_POST["peng"])) {
    //$_POST["peng"]["konten"] = trim(preg_replace('/\s\s+/','', $_POST["peng"]["konten"]));
    $data=array(':judul'=>$_POST["peng"]["judul"],':konten'=>$_POST["peng"]["konten"]);
    sql_dbop($connect,"UPDATE options SET txt=:judul, txt2=:konten WHERE set_var='pengumuman'",$data);
    $opt["text"]=date("H:i:s")." Teks pengumuman di dashboard telah diperbarui.";
  }
  if(isset($_POST["kadis"])){
    $data=array(":txt"=>json_encode($_POST["kadis"]));
    sql_dbop($connect,"UPDATE options SET txt=:txt WHERE set_var='kadis'",$data);
    $opt["text"]=date("H:i:s")." Kredensial Kepala Dinas Pendidikan dan Kebudayaan telah diperbarui.";
  }
  echo json_encode($opt);
}
?>