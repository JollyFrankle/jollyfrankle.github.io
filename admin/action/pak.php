<?php
/* ———————————————————————————————————————— //
-- admin/action/pak.php
   Kontrol data dari halaman:
   - admin/pak.php
   - admin/pak-list.php
// ———————————————————————————————————————— */
include('../database_connection.php');
session_start();
if(!isset($_POST["action"]) || !isset($_SESSION["adm_id"])){
include('../../403.html');
http_response_code(403);
} else {
  if($_POST["action"]=="hapus") {
    $query = "DELETE FROM tbl_pak WHERE pak_id=:id AND pak_admid=:adm";
    $st = $connect->prepare($query);
		$st->execute(array(":id"=>$_POST["pak_id"],":adm"=>$_SESSION["adm_id"]));
		if($st->rowCount()!=1)
      $output=array(
        "error" =>  true,
        "text"  =>  date("H:i:s").": Data tidak berhasil dihapus. Mohon tidak <i>spam</i> tombol ini. Silakan ulangi permintaan Anda, atau refresh halaman ini."
      );
		else $output=array(
		    "success"=> true,
		    "text"  =>  date("H:i:s").": Data PAK berhasil dihapus. Anda saat ini menggunakan <b>".sql_value($connect,"SELECT count(pak_id) FROM tbl_pak WHERE pak_admid=:adm",array(":adm"=>$_SESSION["adm_id"])).' dari 10</b> slot yang tersedia.'
		  );
  }
  if($_POST["action"]=="fetch_list") {
    $query = "
    SELECT * FROM tbl_pak
    WHERE pak_admid = :adm
		";
		$st = $connect->prepare($query);
		$st->execute(array(":adm"=>$_SESSION["adm_id"]));
		$res = $st->fetchAll();
		$data = array();
		foreach($res as &$r)
		{
		  $r=array_map("filter_xsshtml",$r);
		  $content=json_decode($r["pak_content"],true);
		  // line 42-46: belum final
		  $m="";
  		if(date("Y-".sprintf("%02d",$content["monthsel"]))>date("Y-m"))
        $m=date("Y-".sprintf("%02d",$content["monthsel"])); else $m=date("Y-".sprintf("%02d",$content["monthsel"]),strtotime("+1 year"));
        $tmtpt_naikpgkt="";
        $tmtpt_naikpgkt=((date("Y") - date("Y",strtotime($m)))*12)+(date("m")-date("m",strtotime($m)));
		  $b_view='<a href="pak?id_pak='.$r["pak_id"].'" class="btn btn-sm btn-primary">Lihat/Ubah</a>';
		  $b_hapus='<button class="btn btn-danger btn-sm hps_pak" data-id="'.$r["pak_id"].'">Hapus</button>';
		  $rdt=array();
		  $rdt[]='<h6 class="mb-0">'.$content["gtk_nama"].'</h6><div>'.$content["gtk_sekolah"].'</div>';
		  $rdt[]='<b><abbr title="TMT Pangkat Terakhir">TMTPT</abbr>: '.date("d/m/Y",strtotime($content["gtk_tmtpt"])).'</b><br/><small>Masa kerja: '.$content["mkg_lama_t"].'t, '.$content["mkg_lama_b"].'b</small>';
		  $rdt[]=$r["pak_lastmod"];
		  $rdt[]=$b_view.$b_hapus.'<div class="small">Ukuran: <b>'.strlen($r["pak_content"]).' bita</b>.</div>';
		  $data[]=$rdt;
		}
		unset($r);
		$output=array("data"=>$data);
  }
  if($_POST["action"]=="fetch") {
    if($_POST["id_gtk"]) {
      if($_SESSION["adm_level"]==2 && (!in_array(sql_value($connect, "SELECT tbl_sekdb.dt_dati2 FROM tbl_sekdb,tbl_gtk WHERE tbl_gtk.gtk_id=:gtk AND tbl_gtk.gtk_npsn=tbl_sekdb.dt_npsn",array(":gtk"=>$_POST["id_gtk"])),$_SESSION["adm_dati2"]) || !in_array(sql_value($connect, "SELECT tbl_sekdb.dt_jp FROM tbl_sekdb,tbl_gtk WHERE tbl_gtk.gtk_id=:gtk AND tbl_gtk.gtk_npsn=tbl_sekdb.dt_npsn",array(":gtk"=>$_POST["id_gtk"])),$_SESSION["adm_jp"])))
      {
        $output=array(
          "error"   =>  true,
          "text"    =>  date("H:i:s").": GTK tidak ditemukan.",
          "action"  =>  "tambah",
          "btn_txt" =>  "Tambah ke Daftar Tunggu"
        );
      } else {
        $query = "
        SELECT * FROM tbl_gtk 
        WHERE gtk_id = :gtk
        ";
        $st = $connect->prepare($query);
        $st->execute(array(":gtk"=>$_POST["id_gtk"]));
        $res = $st->fetchAll();
        if($st->rowCount()!=1) $err_text.="Tidak ditemukan data yang sesuai untuk permintaan ini. ";
        foreach($res as $r) {
          $ttl=json_decode($r["gtk_ttl"],true);
          if($r["gtk_jk"]=="L")$jk="Laki-laki";
          if($r["gtk_jk"]=="P")$jk="Perempuan";
          $kual=json_decode($r["gtk_kual"],true);
          function pen_ter($r){
            if($r=='SD')$r1='Sekolah Dasar';
            if($r=='SMP')$r1='Sekolah Menengah Pertama';
            if($r=='SMA')$r1='Sekolah Menengah Atas';
            if($r=='D1')$r1='Ahli Pratama';
            if($r=='D2')$r1='Ahli Muda';
            if($r=='D3')$r1='Ahli Madya';
            if($r=='D4')$r1='Ahli';
            if($r=='S1')$r1='Sarjana';
            if($r=='S2')$r1='Magister';
            if($r=='S3')$r1='Doktor';
            return $r1;
          }
          $jns=json_decode($r["gtk_jenis"],true);
          $c_jns="";
          if($jns[0]=="Kepsek")$c_jns="Kepala Sekolah";
          if($jns[0]=="Guru")$c_jns="Guru Mata Pelajaran ".$jns[1];
          if($jns[0]=="Tendik")$c_jns="Tenaga Kependidikan";
          // Error Validation dan output:
          if($jns[0]!="Guru") $err_text.="GTK yang Anda pilih <b>bukan merupakan seorang guru</b>. ";
          if($r["statpeg"]!="PNS")$err_text.="GTK yang Anda pilih <b>bukan seorang PNS</b>. ";
          if(!$err_text) {
            $data=array(
              "gtk_nama"  =>  $r["gtk_nama"],
              "gtk_nip"   =>  $r["gtk_nip"],
              "gtk_nuptk" =>  $r["gtk_nuptk"],
              "gtk_karpeg"=>  $r["gtk_karpeg"],
              "gtk_tmtpt" =>  $r["gtk_tmtpt"],
              "gtk_ttl"   =>  $ttl[0].', '.date("d ",strtotime($ttl[1])).bulan(date("m",strtotime($ttl[1]))).date(" Y",strtotime($ttl[1])),
              "gtk_jk"    =>  $jk,
              "gtk_pt"    =>  pen_ter($kual[0]).' '.$kual[1],
              "gtk_jenis" =>  $c_jns,
              "gtk_sekolah"=> sql_value($connect, "SELECT dt_nama FROM tbl_sekdb WHERE dt_npsn=:npsn",array(":npsn"=>$r["gtk_npsn"])),
              "gol_rg"    =>  $r["gtk_gol"]
            );
            $output=array(
              "success" =>  true,
              "action"  =>  "tambah",
              "data"    =>  $data,
              "btn_txt" =>  "Tambah ke Daftar Tunggu"
            );
          }
        }
        if($err_text) {
            $output=array(
              "error"   =>  true,
              "text"    =>  date("H:i:s").": ".$err_text,
              "action"  =>  "tambah",
              "btn_txt" =>  "Tambah ke Daftar Tunggu"
            );
          }
      }
    }
    if($_POST["id_pak"]) {
      $query = "
      SELECT pak_content FROM tbl_pak 
      WHERE pak_id = :id AND pak_admid='".$_SESSION["adm_id"]."'
      ";
      $st = $connect->prepare($query);
      $st->execute(array(":id"=>$_POST["id_pak"]));
      $res = $st->fetchAll();
      if($st->rowCount()!=1)
      $output=array(
        "error"   =>  true,
        "text"    =>  date("H:i:s").": Tidak ditemukan data yang sesuai untuk permintaan ini.",
        "btn_txt" =>  "Tambah ke Daftar Tunggu"
      );
      else foreach($res as $r) {
        $output=array(
          "success" =>  true,
          "action"  =>  "update",
          "data"    =>  json_decode($r["pak_content"],true),
          "pak_id"  =>  $_POST["id_pak"],
          "btn_txt" =>  "Perbarui ke Daftar Tunggu"
        );
      }
    }
  }
  if($_POST["action"]=="update" || $_POST["action"]=="tambah") {
    $data=array(
      ":pak_content"=>  json_encode($_POST["pak"]),
      ":pak_lastmod"=>  date("Y-m-d H:i:s"),
      ":pak_admid"  => $_SESSION["adm_id"]
    );
    if($_POST["action"]=="tambah") {
      if(sql_value($connect, "SELECT count(pak_id) FROM tbl_pak WHERE pak_admid='".$_SESSION["adm_id"]."'")>10) {
        $output = array(
          'error'		  =>	true,
          'text'      =>  date("H:i:s").': Anda sudah mencapai limit jumlah GTK. Silakan hapus GTK yang lain agar bisa menambahkan GTK ini.',
          "btn_txt"   =>  "Tambah ke Daftar Tunggu"
        );
      } else {
        $query="INSERT INTO tbl_pak (pak_admid, pak_content, pak_lastmod) VALUES (:pak_admid, :pak_content, :pak_lastmod)";
        $st = $connect->prepare($query);
        if($st->execute($data))
        {
          $output = array(
            'success'		=>	true,
            'text'      =>  date("H:i:s").': PAK Berhasil ditambahkan ke basis data. Anda akan diarahkan ke halaman penyuntingan selanjutnya.',
            'redirect'  =>  'pak?id_pak='.$connect->lastInsertId()
          );
        }
      }
    }
    if($_POST["action"]=="update") {
      $data[":pak_id"]=$_POST["pak_id"];
      $query="
        UPDATE tbl_pak SET pak_content=:pak_content, pak_lastmod=:pak_lastmod
        WHERE pak_id=:pak_id
        AND pak_admid=:pak_admid";
      $st = $connect->prepare($query);
      $st->execute($data);
      if($st->rowCount()!=1)
        $output=array(
          "error"   =>  true,
          "text"    =>  date("H:i:s").": Sistem gagal memproses permintaan Anda. Mohon tidak <i>spam</i> tombol ini. Silakan ulangi permintaan Anda, atau refresh halaman ini.",
          "btn_txt" =>  "Perbarui ke Daftar Tunggu"
        );
      else {
        $output = array(
          "success"		=>	true,
          "text"      =>  date("H:i:s").": Formulir PAK berhasil diperbarui. Penyimpanan terpakai: <b>".strlen(json_encode($_POST["pak"]))." bita</b>.",
          "btn_txt"   =>  "Perbarui ke Daftar Tunggu"
        );
      }
    }
  }
  echo json_encode($output);
}