<body style="font-family:Roboto">
<form method="post">
<select multiple name="nama[]">
                    <option value="06">Kabupaten Alor</option>
                    <option value="05">Kabupaten Belu</option>
                    <option value="09">Kabupaten Ende</option>
                    <option value="07">Kabupaten Flores Timur</option>
                    <option value="01">Kabupaten Kupang</option>
                    <option value="14">Kabupaten Lembata</option>
                    <option value="22">Kabupaten Malaka</option>
                    <option value="16">Kabupaten Manggarai Barat</option>
                    <option value="20">Kabupaten Manggarai Timur</option>
                    <option value="11">Kabupaten Manggarai</option>
                    <option value="17">Kabupaten Nagekeo</option>
                    <option value="10">Kabupaten Ngada</option>
                    <option value="15">Kabupaten Rote Ndao</option>
                    <option value="21">Kabupaten Sabu Raijua</option>
                    <option value="08">Kabupaten Sikka</option>
                    <option value="19">Kabupaten Sumba Barat Daya</option>
                    <option value="13">Kabupaten Sumba Barat</option>
                    <option value="18">Kabupaten Sumba Tengah</option>
                    <option value="12">Kabupaten Sumba Timur</option>
                    <option value="03">Kabupaten Timor Tengah Selatan</option>
                    <option value="04">Kabupaten Timor Tengah Utara</option>
                    <option value="60">Kota Kupang</option>
</select>
<input type="submit"/>
</form>
<? include'../database_connection.php';
if($_POST["nama"]){?>
<h3>
    <?foreach($_POST["nama"] as $res){
        echo dati2($res).', ';
    }
    echo implode(',', array_map('intval', $_POST["nama"]));?>
</h3>
<?
// Check if KODE_KAB SEKOLAH di bawah tanggungjawab admin tsb:
if(in_array('05',$_POST["nama"])) echo "... yaass";
// Get all value where sekolah is admin dati2's tanggungjawab:
$query = "SELECT * FROM tbl_sekdb WHERE dt_dati2 IN(".implode(',', array_map('intval', $_POST["nama"])).")";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Dati2</th>
            <th>Dati3</th>
            <th>Nama</th>
            <th>DapodikID</th>
            <th>Last Update</th>
        </tr>
    </thead>
    <tbody>
<?foreach($result as $r){?>
<tr>
    <td><?=$r["dt_id"];?></td>
    <td><?=dati2($r["dt_dati2"]);?></td>
    <td><?=$r["dt_dati3"];?></td>
    <td><?=$r["dt_nama"];?></td>
    <td><?=$r["dt_dapodikid"];?></td>
    <td><?=$r["dt_lastupdate"];?></td>
</tr>
    <?}?>
    </tbody>
</table>
<?}?>
</body>