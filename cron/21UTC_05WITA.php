<?php
include('../admin/database_connection.php');
$query = "
UPDATE options 
SET val1 = '0'
WHERE set_var = 'maintenance'
";
$statement = $connect->prepare($query);
$statement->execute();

echo date("d/m/Y H:i:s").': Cron Job: 21:00 UTC / 05:00 WITA - Server berhasil diaktifkan kembali.';
die();
?>