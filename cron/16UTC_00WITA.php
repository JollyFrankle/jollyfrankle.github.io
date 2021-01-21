<?php
include('../admin/database_connection.php');
$query = "
UPDATE options 
SET val1 = '1'
WHERE set_var = 'maintenance'
";
$statement = $connect->prepare($query);
$statement->execute();

echo date("d/m/Y H:i:s").': Cron Job: 16:00 UTC / 00:00 WITA - Server berhasil dinonaktifkan.';
die();
?>