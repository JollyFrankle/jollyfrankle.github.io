<?php 
include('../database_connection.php');
session_start();
// 100% - TAB ok

/* Penjelasan admin level:
0 - Super Admin / Creator
1 - Moderator (Admin dinas)
2 - Pendamping Wilayah
3 - Pengecek Presensi
*/
if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
	if($_POST["action"] == "fetch_terdaftar")
	{
		$query = "
		SELECT tbl_sekolah.sek_email,tbl_sekolah.sek_npsn,tbl_sekolah.sek_lastmod,tbl_sekolah.sek_aktif,tbl_sekdb.dt_nama,tbl_sekdb.dt_jp,tbl_sekdb.dt_dati2,tbl_sekdb.dt_dati3
				FROM tbl_sekolah,tbl_sekdb
				WHERE tbl_sekolah.sek_npsn=tbl_sekdb.dt_npsn 
		";
		$_POST["dati2"]=addslashes($_POST["dati2"]);
		$_POST["tipe"]=addslashes($_POST["tipe"]);
		$_POST["aktif"]=addslashes($_POST["aktif"]);
		if($_SESSION["adm_level"]!=2)
			if($_POST["dati2"]) $query.= "AND dt_dati2 = '".$_POST["dati2"]."' ";
			if($_POST["tipe"]) $query.= "AND dt_jp = '".$_POST["tipe"]."' ";
		if($_SESSION["adm_level"]==2){
			if($_POST["dati2"] && in_array($_POST["dati2"],$_SESSION["adm_dati2"]))
				$query.= "AND dt_dati2 = '".$_POST["dati2"]."' ";
			else $query.= "AND dt_dati2 IN(".implode(',', array_map('intval', $_SESSION["adm_dati2"])).") ";
			if($_POST["tipe"] && in_array($_POST["tipe"],$_SESSION["adm_jp"]))
				$query.= "AND dt_jp = '".$_POST["tipe"]."' ";
			else $query.= "AND dt_jp IN(".implode(',', array_map('arrslashes', $_SESSION["adm_jp"])).") ";
		}
		if($_POST["aktif"])
			$query.= "AND sek_aktif = '".($_POST["aktif"]-1)."' ";
		$st = $connect->prepare($query);
		$st->execute();
		$result = $st->fetchAll();
		$data = array();
		$m_now = date("m");
		$m_min1= date("m",strtotime("-1 months"));
		$Y_now = date("Y");
		$Y_min1= date("Y",strtotime("-1 months"));
		foreach($result as $r)
		{
			$rdt = array();
			$p_identitas = '<div style="font-weight:500">'.$r["dt_nama"].'</div>'.'<div class="small">NPSN '.$r["sek_npsn"].'</div>'.'<div class="small">Kontak: '.$r["sek_email"].'</div>';
			
			$b_laporan = '<button class="btn btn-info btn-sm rekap" data-npsn="'.$r["sek_npsn"].'">Rekap Daftar Hadir</button>';
			$b_hapus	 = '<button class="btn btn-danger btn-sm hapus_sek" data-npsn="'.$r["sek_npsn"].'">Hapus</button>';
			$b_view		= '<a href="dftgtk?npsn='.$r["sek_npsn"].'" class="btn btn-primary btn-sm">Daftar GTK</a>';
			$b_otp		 = '<button class="btn btn-success btn-sm otp_sek" nama_sekolah="'.$r["dt_nama"].'" id="'.$r["sek_npsn"].'">Generate OTP</button>';
			if($_SESSION["adm_level"]==3){
					$b_laporan='<div>Periksa presensi:</div><a href="rekap-dh?npsn='.$r["sek_npsn"].'&b='.$m_now.'&t='.$Y_now.'" class="btn btn-sm btn-info">Bulan ini</a><a href="rekap-dh?npsn='.$r["sek_npsn"].'&b='.$m_min1.'&t='.$Y_min1.'" class="btn btn-sm btn-info">Bulan lalu</a>';
			}
			
			if($r["sek_aktif"]==1)$aktifcheck='';else $aktifcheck='selected';
			$p_aktif = '
			<div class="input-group-sm d-flex w-100 justify-content-between border-top mt-1 pt-1 align-items-center">
				<small class="text-nowrap mr-1">Status akun:</small>
				<select class="edit_aktif custom-select form-control" data-npsn="'.$r["sek_npsn"].'">
					<option value="1">Aktif</option>
					<option value="0" '.$aktifcheck.'>Belum aktif</option>
				</select>
			</div>';
			if($_SESSION["adm_level"]==2){$b_otp='';}
						if($_SESSION["adm_level"]==3){$b_hapus='';$b_view='';$b_otp='';$p_aktif='';}
			// URUTAN KOLOM DALAM TABEL
			$rdt[] = $p_identitas.$p_aktif;
			$rdt[] = dati2($r["dt_dati2"]).'<div class="small">Kec. '.$r["dt_dati3"].'</div>';
			$rdt[] = sql_value($connect, "SELECT count(gtk_id) FROM tbl_gtk WHERE gtk_npsn = '".$r["sek_npsn"]."'");
			$rdt[] = $r["sek_lastmod"];
			$rdt[] = $b_laporan.$b_hapus.$b_view.$b_otp;
			$data[] = $rdt;
		}
		$opt = array("data"=>$data);
	}
	
	$data=array(":npsn"=>$_POST["npsn"]);
	// VALIDASI: Hanya admin wilayah dengan NPSN yang sesuai dengan dati2 dan jenj pend, admin dinas, dan webmaster yang dapat akses.
	if(($_SESSION["adm_level"]==2 && in_array(sql_value($connect,"SELECT dt_dati2 FROM tbl_sekdb WHERE dt_npsn=:npsn",$data),$_SESSION["adm_dati2"]) && in_array(sql_value($connect,"SELECT dt_jp FROM tbl_sekdb WHERE dt_npsn=:npsn",$data),$_SESSION["adm_jp"])) || $_SESSION["adm_level"]==1 || $_SESSION["adm_level"]==0)
	{
	  // TINDAKAN: Mengubah status keaktifan akun utama sekolah (sek_level=0 berarti akun utama sekolah)
		if($_POST["action"]=='edit_sek_aktif')
		{
      $data[":aktif"]=$_POST["sek_aktif"];
			sql_dbop($connect, "UPDATE tbl_sekolah SET sek_aktif=:aktif WHERE sek_npsn=:npsn AND sek_level=0",$data);
			if($_POST["sek_aktif"]==0)
			$keaktifan='telah dinonaktifkan.';else $keaktifan='telah diaktifkan.';
			$opt = array(
        "success" =>  true,
        "text"    =>  date("H:i:s").': Akun ini '.$keaktifan
			);
		}
		
		// TINDAKAN: Membuat kode OTP
		if($_POST["action"]=="generateOTP" && $_SESSION["adm_level"]!=2)
		{
			$kodeOTP=rand(100,999).'-'.rand(100,999);
			$data[":otp"]=$kodeOTP;
			sql_dbop($connect, "UPDATE tbl_sekolah SET sek_otp=:otp WHERE sek_npsn=:npsn AND sek_level=0",$data);
			$opt = array(
        "success" =>  true,
        "otp"     =>  $kodeOTP
			);
		}
		
		// TINDAKAN: Menghapus sekolah
		if($_POST["action"]=="hapus")
		{
			sql_dbop($connect, "DELETE FROM tbl_sekolah WHERE sek_npsn = :npsn",$data);
			sql_dbop($connect, "DELETE FROM tbl_gtk WHERE gtk_npsn = :npsn",$data);
			sql_dbop($connect, "DELETE FROM tbl_dft_hadir WHERE dfhd_npsn = :npsn",$data);
			sql_dbop($connect, "DELETE FROM tbl_unggahan WHERE ung_npsn = :npsn",$data);
			$opt = array(
        "success" =>  true,
        "text"    =>  date("H:i:s").': Akun '.$_POST["npsn"].' telah dihapus'
			);
		}
	}
	
	// TINDAKAN: Mendapatkan data semua sekolah DI NTT yang diupdate dari Dapodik
	if($_POST["action"] == "fetch_dbdapodik")
	{
		$st = $connect->prepare("SELECT * FROM tbl_sekdb");
		$st->execute();
		$result = $st->fetchAll();
		$data = array();
		foreach($result as $r)
		{
			$rdt = array();
			$rdt[] = dati2($r["dt_dati2"]);
			$rdt[] = $r["dt_dati3"];
			$rdt[] = $r["dt_jp"].' '.$r["dt_status"];
			$rdt[] = $r["dt_npsn"];
			$rdt[] = $r["dt_nama"];
			$rdt[] = $r["dt_lastupdate"];
			$rdt[] = '<a class="btn btn-sm btn-outline-primary" target="_blank" href="https://dapo.kemdikbud.go.id/sekolah/'.$r["dt_dapodikid"].'">Profil Dapodik</a>';
			$data[] = $rdt;
		}
		$opt = array("data"=>$data);
	}
	echo json_encode($opt);
}