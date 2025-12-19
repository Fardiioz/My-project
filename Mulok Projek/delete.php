<?php
include '../koneksi.php';
mysqli_query($conn, "DELETE FROM admin WHERE level = 'off'");
echo "Admin level 'off' dihapus.";
?>