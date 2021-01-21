<?php include('../admin/database_connection.php');
/*
$sql2="
			      SELECT * FROM tbl_dft_hadir
                  WHERE gtk_id='3'
                  AND dfhd_tgl BETWEEN '2020-08-01' AND '2020-08-31'
			      ";
			$statement = $connect->prepare($sql2);
    		$statement->execute();
    		$res2 = $statement->fetchAll(\PDO::FETCH_ASSOC);

// Using the $records array from Example #1
$last_names = array_column($res2, 'dfhd_status', 'dfhd_tgl');

echo $last_names["2020-09-01"];
*/
if('2020-08-27' > date("Y-m-d")) {
    echo '>';
}elseif('2020-08-27' == date("Y-m-d")){
    echo '=';
}elseif('2020-08-27' < date("Y-m-d")){
    echo '<';
}

echo date("N", strtotime('2020-08-23'));