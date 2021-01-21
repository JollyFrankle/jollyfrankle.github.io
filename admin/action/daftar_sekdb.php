<?php 
include('../database_connection.php');
session_start();
// OBSOLETE, dipindahkan ke daftar_sekolah.php

if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
if($_POST["action"] == "fetch")
	{
		$statement = $connect->prepare("SELECT * FROM tbl_sekdb");
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		foreach($result as $row)
		{
			$rdt = array();
			// URUTAN KOLOM DALAM TABEL
			$rdt[] = dati2($row["dt_dati2"]);
            $rdt[] = $row["dt_dati3"];
			$rdt[] = $row["dt_jp"].' '.$row["dt_status"];
			$rdt[] = $row["dt_npsn"];
			$rdt[] = $row["dt_nama"];
			$rdt[] = $row["dt_lastupdate"];
			$rdt[] = '<a class="btn btn-sm btn-outline-primary" target="_blank" href="https://dapo.kemdikbud.go.id/sekolah/'.$row["dt_dapodikid"].'">Data Dapodik</a>';
			$data[] = $rdt;
		}
		$output = array(
			"data"				=>	$data
		);
		echo json_encode($output);
	}
}