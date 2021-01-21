<?php 
include('../database_connection.php');
session_start();
$qnpsn=array(':npsn'=>$_POST["npsn"]);

if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"]) || ($_SESSION["adm_level"]==2 && (!in_array(sql_value($connect, "SELECT dt_dati2 FROM tbl_sekdb WHERE dt_npsn=:npsn",$qnpsn),$_SESSION["adm_dati2"]) || !in_array(sql_value($connect, "SELECT dt_jp FROM tbl_sekdb WHERE dt_npsn=:npsn",$qnpsn),$_SESSION["adm_jp"])))){
include('../../403.html');
http_response_code(403);
} else {
  if($_POST["action"] == "fetch")
	{
    $data=array(
      ":npsn" =>  $_POST["npsn"],
      ":awal" =>  $_POST["thn"].'-'.$_POST["bln"]."-01",
      ":akhir"=>  $_POST["thn"].'-'.$_POST["bln"]."-31"
		);
		$q_gtk=sql_valarray($connect,"SELECT distinct(gtk_id) FROM tbl_dft_hadir WHERE dfhd_npsn=:npsn AND (dfhd_tgl BETWEEN :awal AND :akhir)",0,$data);
		if(!empty($q_gtk)) $query = "
      SELECT gtk_id,gtk_no,gtk_nama,gtk_nip,statpeg FROM tbl_gtk
      WHERE gtk_npsn = :npsn
      AND gtk_id IN(".implode(',', array_map('intval',$q_gtk)).")
      ORDER BY gtk_no ASC
		";
		else $query = "
      SELECT gtk_id,gtk_no,gtk_nama,gtk_nip FROM tbl_gtk
      WHERE gtk_npsn = :npsn
      ORDER BY gtk_no ASC";
		$st = $connect->prepare($query);
		$st->execute($qnpsn);
		$result = $st->fetchAll();
		$tb=$_POST["thn"].'-'.$_POST["bln"];
		$data_opt=array();
		foreach($result as &$row)
		{
		  $row=array_map("filter_xsshtml",$row);
			$rdt = array();
			$p_nip = '';
			if($row["gtk_nip"])$p_nip='<div class="small">NIP. '.$row["gtk_nip"].'</div>';
			$r_HD='<div><small class="text-success small">H: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'HD').', </small>';
			$r_TB='<small class="text-danger">A: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'TB').', </small>';
			$r_IZ='<small class="text-primary">I: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'IZ').', </small>';
			$r_SK='<small class="text-primary">S: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'SK').', </small></div>';
			$r_DL='<div><small class="text-primary">D: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'DL').', </small>';
			$r_CT='<small class="text-primary">C: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'CT').', </small>';
			$r_TG='<small class="text-primary">B: '.jlhkeh($connect, $row["gtk_id"], $tb.'-01', $tb.'-31', 'TG').'</small></div>';

			$data[":id"]=$row["gtk_id"];
			$sql2="
				SELECT dfhd_status,dfhd_tgl FROM tbl_dft_hadir
				WHERE gtk_id=:id
				AND (dfhd_tgl BETWEEN :awal AND :akhir)
				AND dfhd_npsn=:npsn
			";
			$st = $connect->prepare($sql2);
			$st->execute($data);
			$res2 = $st->fetchAll(\PDO::FETCH_ASSOC);
			$r2a = array_column($res2, 'dfhd_status', 'dfhd_tgl');

			$rdt[] = $row["gtk_no"];
			$rdt[] = $row["gtk_nama"].$p_nip;
			for($count=1;$count<=date("t",strtotime($tb));$count++) {
				$rdt[] = $r2a[$tb.'-'.sprintf("%02d",$count)];
			}
			$rdt[] = $r_HD.$r_TB.$r_IZ.$r_SK.$r_DL.$r_CT.$r_TG;
			$data_opt[] = $rdt;
		}
		unset($row);
		$opt=array("data"=>$data_opt);
	}
	echo json_encode($opt);
}