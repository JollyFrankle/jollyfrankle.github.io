<?session_start();
include'../../admin/database_connection.php';
if(!$_GET["b"] || !$_GET["t"] || !isset($_SESSION["sek_id"]) || (sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'") && !isset($_SESSION["adm_id"]))){
include('../../403.html');
http_response_code(403);
die();}
$_GET["b"]=addslashes($_GET["b"]);
$_GET["t"]=addslashes($_GET["t"]);
$_GET["kertas"]=addslashes($_GET["kertas"]);
ob_start();
?>
<style>
#tblp td,#tblp th{border:1px solid;padding:.15rem;width:1rem;text-align:center}@page{margin:1cm;}body{margin:0;font-family:Helvetica}#tblp{border-collapse:collapse;width:100%;font-size:10pt}h1,h2,h3{margin:0}h1{font-size:18pt}h2{font-size:14pt}h3{font-size:12pt}#tblp td:nth-child(2){text-align:left}#tblp td:last-child{background:none!important}#tblp th:last-child{background:#ddd!important}.nowrap{white-space: nowrap;}
<?$d1 = 7 - date('N',strtotime($_GET["t"].'-'.$_GET["b"])) + 3;
$dn = date('t',strtotime($_GET["t"].'-'.$_GET["b"])) + 3;
for($i=$d1;$i<=$dn;$i=$i+7)echo '#tblp td:nth-child('.$i.'),#tblp th:nth-child('.$i.'){background:#f5c6cb}';?>
</style>
<div style="text-align:center;margin-bottom:1rem">
  <div style="border-bottom:2px solid #000;margin-bottom:.5rem;padding-bottom:.5rem">
  <img src="data:image/jpeg;base64,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" style="float:left"/>
  <div style="margin-left:50px">
  	<h2>PEMERINTAH PROVINSI NUSA TENGGARA TIMUR</h2>
  	<h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
  	<h1><?=$_SESSION["sek_nama"];?></h1>
  </div>
  </div>
  <h3>REKAPITULASI DAFTAR HADIR</h3>
  <h3 style="text-transform:uppercase">BULAN <?=bulan($_GET["b"]);?> TAHUN <?=$_GET["t"];?></h3>
</div>
<table id="tblp" style="max-width:100%">
  <thead style="background:#ddd;border-bottom:2px solid;">
    <tr>
      <th>No</th>
      <th style="min-width:260px">Nama GTK</th>
      <?for($count=1;$count<=date("t",strtotime($_GET["t"].'-'.$_GET["b"]));$count++)echo'<th>'.$count.'</th>';?>
    </tr>
  </thead>
  <tbody>
    <?
    function k_clean($x){
      if($x=='HD')return'.';
      if($x=='TB')return'A';
      if($x=='DL')return'D';
      if($x=='SK')return'S';
      if($x=='IZ')return'I';
      if($x=='TG')return'B';
      if($x=='CT')return'C';
		}
    $q_gtk=sql_valarray($connect,"SELECT distinct(gtk_id) FROM tbl_dft_hadir WHERE dfhd_npsn='".$_SESSION["sek_npsn"]."' AND (dfhd_tgl BETWEEN '".$_GET["t"].'-'.$_GET["b"].'-01'."' AND '".$_GET["t"].'-'.$_GET["b"].'-31'."')");
    if(!empty($q_gtk))$query = "
		SELECT gtk_id,gtk_no,gtk_nama,gtk_nip,statpeg FROM tbl_gtk
		WHERE gtk_npsn = '".$_SESSION["sek_npsn"]."'
		AND gtk_id IN(".implode(',', array_map('intval',$q_gtk)).")
		ORDER BY gtk_no ASC
		";
		else $query = "
		SELECT gtk_id,gtk_no,gtk_nama,gtk_nip,statpeg FROM tbl_gtk
		WHERE gtk_npsn = '".$_SESSION["sek_npsn"]."'
		ORDER BY gtk_no ASC";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$tb=$_GET["t"].'-'.$_GET["b"];
		$data = array();
		foreach($result as $row)
		{
			$rdt = array();
			$p_nip = '';
			if($row["statpeg"] == "PNS" && $row["gtk_nip"])$p_nip='NIP. '.$row["gtk_nip"];
			/*$id=$row["gtk_id"];
			$r_HD='<div>H: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'HD').', </small>';
			$r_TB='<small class="text-danger">A: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'TB').', </small>';
			$r_IZ='<small class="text-primary">I: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'IZ').', </small>';
			$r_SK='<small class="text-primary">S: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'SK').', </small></div>';
			$r_DL='<div><small class="text-primary">D: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'DL').', </small>';
			$r_CT='<small class="text-primary">C: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'CT').', </small>';
			$r_TG='<small class="text-primary">B: '.jlhkeh($connect, $id, $tb.'-01', $tb.'-31', 'TG').'</small></div>';*/
			
			$sql2="
			SELECT dfhd_status,dfhd_tgl FROM tbl_dft_hadir
            WHERE gtk_id='".$row["gtk_id"]."'
            AND dfhd_tgl BETWEEN '".$tb.'-01'."' AND '".$tb.'-31'."'
			      ";
			$st2 = $connect->prepare($sql2);
    	$st2->execute();
    	$res2 = $st2->fetchAll(\PDO::FETCH_ASSOC);
      $r2a = array_column($res2, 'dfhd_status', 'dfhd_tgl');?>
      <tr style="min-height:2rem">
        <td><?=$row["gtk_no"];?></td>
        <td><?=$row["gtk_nama"];?><br/><small class="nowrap"><?=$p_nip?></small></td>
        <?for($count=1;$count<=date("t",strtotime($tb.'-01'));$count++){?>
			    <td><?=k_clean($r2a[$tb.'-'.sprintf("%02d",$count)]);?></td>
			  <?}?>
      </tr>
		<?}
    ?>
  </tbody>
</table>
<div style="font-size:9pt;margin-top:4pt"><i><b>Keterangan:</b> .: Hadir, A: Tanpa Keterangan, S: Sakit, I: Izin, D: Dinas Luar, B: Tugas Belajar, C: Cuti.</i></div>
<div style="margin-top:12pt;margin-left:60%;text-align:center;font-size:10pt">
  <?=sql_value($connect,"SELECT dt_dati3 FROM tbl_sekdb WHERE dt_npsn='".$_SESSION["sek_npsn"]."'").", 1 ".bulan(date(m,strtotime($tb." +1 month")))." ".$_GET["t"];?><br/>
  Kepala Sekolah,
  <div style="margin-top:3rem"><b><u><?=sql_value($connect,"SELECT gtk_nama FROM tbl_gtk WHERE JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[0]'))='Kepsek' AND gtk_npsn='".$_SESSION["sek_npsn"]."'");?></u></b></div>
  <div>NIP. <?=sql_value($connect,"SELECT gtk_nip FROM tbl_gtk WHERE JSON_UNQUOTE(JSON_EXTRACT(gtk_jenis,'$[0]'))='Kepsek' AND gtk_npsn='".$_SESSION["sek_npsn"]."'");?></div>
</div>
<? $content=ob_get_clean();
if(!$_GET["kertas"])$_GET["kertas"]='A4';
require'../../storage/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($content);
$dompdf->setPaper($_GET["kertas"], 'landscape');
$dompdf->render();
$dompdf->stream("SISTER_RDH (".bulan($_GET["b"])." ".$_GET["t"].") (per ".date("Y-m-d H.i.s").").pdf");
die("Silakan tutup halaman ini apabila unduhan telah dimulai.");