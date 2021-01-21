<? include'../database_connection.php';
set_time_limit(0);
$lvl1=json_decode(file_get_contents("https://dapo.kemdikbud.go.id/rekap/dataSekolah?id_level_wilayah=1&kode_wilayah=240000"),true);
$opt=array();
$i=0;
foreach($lvl1 as $lvl1wil) {
    $lvl2=json_decode(file_get_contents("https://dapo.kemdikbud.go.id/rekap/dataSekolah?id_level_wilayah=2&kode_wilayah=".urlencode($lvl1wil["kode_wilayah"])),true);
    foreach($lvl2 as $lvl2wil) {
        $lvl3slb=json_decode(file_get_contents("https://dapo.kemdikbud.go.id/rekap/progresSP?id_level_wilayah=3&bentuk_pendidikan_id=slb&kode_wilayah=".urlencode($lvl2wil["kode_wilayah"])),true);
        foreach($lvl3slb as $r) {
            $sek=array();
            $sek[]=$r["induk_kabupaten"];
            $sek[]=str_replace('Kec. ','',$r["induk_kecamatan"]);
            $sek[]=$r["bentuk_pendidikan"].' '.$r["status_sekolah"];
            $sek[]=$r["npsn"];
            $sek[]=$r["nama"];
            $sek[]='Total: '.($r["ptk"]+$r["pegawai"]).' - Guru: '.$r["ptk"].' - Tendik: '.$r["pegawai"];
            $opt[]=$sek;
        }
        $lvl3sma=json_decode(file_get_contents("https://dapo.kemdikbud.go.id/rekap/progresSP?id_level_wilayah=3&bentuk_pendidikan_id=sma&kode_wilayah=".urlencode($lvl2wil["kode_wilayah"])),true);
        foreach($lvl3sma as $r) {
            $sek=array();
            $sek[]=$r["induk_kabupaten"];
            $sek[]=str_replace('Kec. ','',$r["induk_kecamatan"]);
            $sek[]=$r["bentuk_pendidikan"].' '.$r["status_sekolah"];
            $sek[]=$r["npsn"];
            $sek[]=$r["nama"];
            $sek[]='Total: '.($r["ptk"]+$r["pegawai"]).' - Guru: '.$r["ptk"].' - Tendik: '.$r["pegawai"];
            $opt[]=$sek;
        }
        $lvl3smk=json_decode(file_get_contents("https://dapo.kemdikbud.go.id/rekap/progresSP?id_level_wilayah=3&bentuk_pendidikan_id=smk&kode_wilayah=".urlencode($lvl2wil["kode_wilayah"])),true);
        foreach($lvl3smk as $r) {
            $sek=array();
            $sek[]=$r["induk_kabupaten"];
            $sek[]=str_replace('Kec. ','',$r["induk_kecamatan"]);
            $sek[]=$r["bentuk_pendidikan"].' '.$r["status_sekolah"];
            $sek[]=$r["npsn"];
            $sek[]=$r["nama"];
            $sek[]='Total: '.($r["ptk"]+$r["pegawai"]).' - Guru: '.$r["ptk"].' - Tendik: '.$r["pegawai"];
            $opt[]=$sek;
        }
    }
    $i++;
    if($i==1) break;
}
echo json_encode(array("data"=>$opt));