<?php
include('../../admin/database_connection.php');
session_start();

if(!isset($_POST["action"]) || !isset($_SESSION["sek_id"]) || (sql_value($connect, "SELECT val1 FROM options WHERE set_var = 'maintenance'") && !isset($_SESSION["adm_id"]))){
include('../../403.html');
http_response_code(403);
} else {
	// MODUL: Memeriksa apakah tanggal yang dimasukkan valid
	function ErrorTanggal($tgl,$npsn,$connect)
	{
		if(empty($tgl))
			$err_text .= 'Anda belum mengisi tanggal laporan. ';

		if(DateTime::createFromFormat("Y-m-d",$tgl)==false)
			$err_text .= "Format tanggal tidak valid. Permintaan mungkin cacat. Silakan ulangi. ";

		if(sql_value($connect, "SELECT count(dfhd_tgl) FROM tbl_dft_hadir WHERE dfhd_npsn=:npsn AND dfhd_tgl=:tgl", array(':npsn'=>$npsn,':tgl'=>$tgl)) > 0)
			$err_text .= 'Daftar hadir untuk tanggal ini sudah pernah diisi. <a href="rekap-dh'.date("\?\b\=m\&\\t\=Y&\\t\g\l\=d",strtotime($tgl)).'" class="alert-link">Lihat rekap</a>.';

		if($tgl > date("Y-m-d"))
			$err_text .= 'Tanggal yang Anda tentukan berada di masa depan. ';

		if(date("N", strtotime($tgl)) == 7)
			$err_text .= 'Tanggal yang Anda tentukan bukan hari belajar efektif. ';

		if($tgl < date("Y-m-d",strtotime(" -30 days")))
		  $err_text .= 'Anda tidak diizinkan mengisi presensi yang telah berusia lebih dari 30 hari. ';

		if($err_text) return $err_text; else return false;
	}
	// TINDAKAN: Mengunggah presensi baru
	if($_POST["action"]=="unggah_baru")
	{
		$data=array(
			":npsn" =>	$_SESSION["sek_npsn"],
			":awal" =>	date("Y-m-01",strtotime($_POST["dfhd_tgl"])),
			":akhir"=>	date("Y-m-31",strtotime($_POST["dfhd_tgl"]))
		);
		// Cek daftar GTK (sudah pernah mengunggah presensi dalam bulan tersebut):
		$val_gtk=sql_valarray($connect,"SELECT distinct(gtk_id) FROM tbl_dft_hadir WHERE dfhd_npsn=:npsn AND (dfhd_tgl BETWEEN :awal AND :akhir)",0,$data);
		// Cek daftar GTK (belum pernah mengunggah presensi dalam bulan tersebut):
		if(empty($val_gtk)) $val_gtk=sql_valarray($connect,"SELECT distinct(gtk_id) FROM tbl_gtk WHERE gtk_npsn=:npsn",0,array(":npsn"=>$_SESSION["sek_npsn"]));
		
		$inp_gtk=array_map('intval', $_POST["gtk_id"]);
		sort($val_gtk); sort($inp_gtk);
		if(ErrorTanggal($_POST["dfhd_tgl"],$_SESSION["sek_npsn"],$connect))
		{
			$opt = array(
				'error' =>	true,
				"text"	=>	date("H:i:s: ").ErrorTanggal($_POST["dfhd_tgl"],$_SESSION["sek_npsn"],$connect)
			);
		}
		elseif($val_gtk!==$inp_gtk) // VALIDASI: Apakah daftar GTK yang akan diunggah sudah sesuai dengan yang seharusnya?
		{
			$opt = array(
				'error' =>	true,
				"text"	=>	date("H:i:s: ")."Mohon maaf. Anda tidak diperbolehkan memodifikasi daftar ini secara ilegal.",
				"debug" =>	"GTK_ID:".json_encode($inp_gtk)."\nDFT_GTK:".json_encode($val_gtk)."\nTGL DH:".date("Y-m-01",strtotime($_POST["dfhd_tgl"]))
			);
		}
		else
		{
		  $dfhd_status=array();
			for($id=0;$id<count($_POST["gtk_id"]);$id++)
			{
			  $content=array();
			  $content[]=intval($_POST["gtk_id"][$id]);
			  $content[]=substr($_POST["status_".$_POST["gtk_id"][$id]],0,2);
			  $dfhd_status[]=$content;
				$data = array(
					':gtk_id'       =>	$_POST["gtk_id"][$id],
					':dfhd_status'  =>	$_POST["status_".$_POST["gtk_id"][$id]],
					':dfhd_ket'     =>	substr($_POST["ket_".$_POST["gtk_id"][$id]],0,50),
					':dfhd_tgl'		=>	$_POST["dfhd_tgl"],
					':dfhd_npsn'	=>	$_SESSION["sek_npsn"]
				);
				$query = "
					INSERT INTO tbl_dft_hadir 
					(gtk_id, dfhd_status, dfhd_tgl, dfhd_ket, dfhd_npsn) 
					VALUES (:gtk_id, :dfhd_status, :dfhd_tgl, :dfhd_ket, :dfhd_npsn)
				";
				$st = $connect->prepare($query);
				$st->execute($data);
			}
			$dt_new=array(
			  ":dfhd_npsn"    =>  $_SESSION["sek_npsn"],
			  ":dfhd_content" =>  json_encode($dfhd_status),
			  ":dfhd_tanggal" =>  $_POST["dfhd_tgl"],
			  ":dfhd_tglupload"=> date("Y-m-d H:i:s")
      );
      $query = "
				INSERT INTO tbl_dfhd_status 
				(dfhd_npsn, dfhd_content, dfhd_tanggal, dfhd_tglupload) 
				VALUES (:dfhd_npsn, :dfhd_content, :dfhd_tanggal, :dfhd_tglupload)
			";
			$st = $connect->prepare($query);
			$st->execute($dt_new);
			
			sql_dbop($connect, "UPDATE tbl_sekolah SET sek_lastmod = '".date("Y-m-d H:i:s")."' WHERE sek_id = '".$_SESSION["sek_id"]."'");
			$opt = array(
				"success"		=>	true,
				"text"			=>	date("H:i:s").': Daftar hadir untuk tanggal '.date("d/m/Y", strtotime($_POST["dfhd_tgl"])).' telah diunggah.',
				"checklink" =>	"rekap-dh?b=".date("m",strtotime($_POST["dfhd_tgl"]))."&t=".date("Y",strtotime($_POST["dfhd_tgl"]))."&tgl=".date("d",strtotime($_POST["dfhd_tgl"]))
			);
		}
	}
	// TINDAKAN: Mendapatkan data untuk dipresensi
	if($_POST["action"] == "fetch")
	{
		if(ErrorTanggal($_POST["tanggal"],$_SESSION["sek_npsn"],$connect))
		{
			$opt = array(
				"error" =>	true,
				"text"	=>	date("H:i:s: ").ErrorTanggal($_POST["tanggal"],$_SESSION["sek_npsn"],$connect)
			);
		}
		else
		{
			$blnthn=date("Y-m",strtotime($_POST["tanggal"]));
			$data=array(":npsn"=>$_SESSION["sek_npsn"]);
			$q_gtk=sql_valarray($connect,"SELECT distinct(gtk_id) FROM tbl_dft_hadir WHERE dfhd_npsn=:npsn AND (dfhd_tgl BETWEEN '".$blnthn."-01' AND '".$blnthn."-31')",0,$data);
			if(!empty($q_gtk)) $query = "
				SELECT gtk_no, gtk_nama, gtk_id, gtk_nip FROM tbl_gtk
				WHERE gtk_npsn = :npsn
				AND gtk_id IN(".implode(',', array_map('intval',$q_gtk)).")
				ORDER BY gtk_no ASC
			";
			else $query = "
				SELECT gtk_no, gtk_nama, gtk_id, gtk_nip FROM tbl_gtk
				WHERE gtk_npsn = :npsn
				ORDER BY gtk_no ASC";
			$st = $connect->prepare($query);
			$st->execute($data);
			$res = $st->fetchAll();
			$json_data=array();
			if($st->rowCount()>0)
			{
				foreach($res as &$r)
				{
          $r=array_map("filter_xsshtml",$r);
          $rdt=array();
					/*if($r["gtk_nip"]) $p_nip="NIP. ".$r["gtk_nip"]; else $p_nip="";
					$trow.="<tr>";
					$trow.="<td>".$r["gtk_no"]."<input type=hidden name='gtk_id[]' value=".$r["gtk_id"]." /></td>";
					$trow.="<td><div style='font-weight:500'>".$r["gtk_nama"]."</div>".$p_nip."</td>";
					$trow.="<td><select class='ketdfhd' gtkid='".$r["gtk_id"]."' name='status_".$r["gtk_id"]."'></select></td>";
					$trow.="<td><input class='ket-field' id='ket_".$r["gtk_id"]."' name='ket_".$r["gtk_id"]."'/></td>";
					$trow.="</tr>";*/
					$rdt[]=$r["gtk_id"];
					$rdt[]=$r["gtk_no"];
					$rdt[]=$r["gtk_nama"];
					$rdt[]=$r["gtk_nip"];
					$json_data[]=$rdt;
				}
				unset($r);
				$opt=array(
					"success" =>	true,
					"text"		=>	date("H:i:s").": Tanggal tersedia. Selamat mengisi presensi untuk ".date("d/m/Y.",strtotime($_POST["tanggal"])),
					//"tbody"	 =>	$trow,
					"tanggal" =>  date(d,strtotime($_POST["tanggal"]))." ".bulan(date(m,strtotime($_POST["tanggal"])))." ".date(Y,strtotime($_POST["tanggal"])),
					"data"    =>  $json_data
				);
			}
			else
			{
				$opt=array(
					"error" =>	true,
					"text"	=>	date("H:i:s").": Tampaknya tidak ada GTK yang dapat dipresensi untuk tanggal ini. Apabila Anda belum memiliki GTK, silakan <a href='daftar-gtk' class='alert-link'>klik di sini untuk menambahkan GTK</a>.",
				);
			}
		}
	}
	echo json_encode($opt);
}