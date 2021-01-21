<?include('../database_connection.php');
session_start();
// 100% converted, TAB ok

// VALIDASI: Apabila seorang "pengeola presensi", larang.
if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"]) || $_SESSION["adm_level"]==3){
include('../../403.html');
http_response_code(403);
} else {
	// TINDAKAN: Dapatkan daftar unggahan dari database
	if($_POST["action"]=='fetch_ung')
	{
		$query="
			SELECT tbl_unggahan.*, tbl_sekdb.dt_nama, tbl_sekdb.dt_npsn
			FROM tbl_unggahan,tbl_sekdb
			WHERE tbl_unggahan.ung_npsn = tbl_sekdb.dt_npsn
		";
		$_POST["dati2"]=addslashes($_POST["dati2"]);
		$_POST["tipe"]=addslashes($_POST["tipe"]);
		$_POST["aktif"]=addslashes($_POST["aktif"]);
		// QUERY: Jenjang pendidikan dan Dati 2 sekolah untuk admin umum/dinas dan admin wilayah
		if($_SESSION["adm_level"]!=2)
		{
			if($_POST["tipe"]) $query.= "AND dt_jp = '".$_POST["tipe"]."' ";
			if($_POST["dati2"]) $query.= "AND dt_dati2 = '".$_POST["dati2"]."' ";
		}
		if($_SESSION["adm_level"]==2)
		{
			if($_POST["dati2"] && in_array($_POST["dati2"],$_SESSION["adm_dati2"]))
				$query.= "AND dt_dati2 = '".$_POST["dati2"]."' ";
			else $query.= "AND dt_dati2 IN(".implode(',', array_map('intval', $_SESSION["adm_dati2"])).") ";
			if($_POST["tipe"] && in_array($_POST["tipe"],$_SESSION["adm_jp"]))
				$query.= "AND dt_jp = '".$_POST["tipe"]."' ";
			else $query.= "AND dt_jp IN(".implode(',', array_map('arrslashes', $_SESSION["adm_jp"])).") ";
		}
		$st = $connect->prepare($query);
		$st->execute();
		$result = $st->fetchAll();
		$data = array();
		foreach($result as &$r)
		{
		  $r=array_map("filter_xsshtml",$r);
			$rdt = array();
			$p_tipe = '';
			if($r['ung_tipe']=='PDH')$p_tipe='Pertanggungjawaban Daftar Hadir';
			$p_desc = "<i class='text-muted'>Tidak ada deskripsi tentang berkas.</i>";
      if(!empty($r['ung_desc'])) $p_desc=$r['ung_desc'];
			$btn_view = '<a class="btn btn-primary btn-sm" target="_blank" href="../storage/uploads/'.$r['ung_file'].'">Tampilkan</a>';
			$btn_delete = '<button class="btn btn-danger btn-sm b_unghps" data-nama="'.$r['ung_file'].'">Hapus</button>';

			$rdt[] = '<h6 class="mb-0">'.$r["dt_nama"].'</h6><div class="small">('.$r["dt_npsn"].')</div>';
			$rdt[] = $p_tipe;
			$rdt[] = $p_desc;
			$rdt[] = $r['ung_periode'];
			$rdt[] = $r["ung_tgl"];
			$rdt[] = $btn_view.$btn_delete;
			$data[] = $rdt;
		}
		unset($r);
		$opt = array("data"=>$data);
	}
	// TINDAKAN: Menghapus berkas dari penyimpanan dan database
	if($_POST["action"]=='hapus_berkas')
	{
		$query = "
			DELETE FROM tbl_unggahan 
			WHERE ung_file = :nama
		";
		$st = $connect->prepare($query);
		$st->execute(array(":nama"=>$_POST["ung_nama"]));
		if($st->rowCount()==1)
		{
			$opt=array(
				"success" =>	true,
				"text"		=>	date("H:i:s").': Unggahan berkas telah dihapus secara permanen.'
			);
			unlink('../../storage/uploads/'.$_POST["ung_nama"]);
		}
		else
			$opt=array(
				"error"   =>	true,
				"text"		=>	date("H:i:s").': Data tidak ditemukan atau permintaan cacat. Silakan ulangi permintaan Anda.'
			);
	}
	echo json_encode($opt);
}