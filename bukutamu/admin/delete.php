<?php
include '../koneksi.php';

$query = "DELETE FROM admin WHERE level = 'off'";
$result = mysqli_query($conn, $query);

if ($result) {
    $message = "Admin level 'off' berhasil dihapus.";
} else {
    $message = "Gagal menghapus: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Admin</title>
    <script>
        // Tampilkan popup setelah halaman dimuat
        window.onload = function() {
            alert("<?= addslashes($message) ?>");
            window.location.href = "t_admin.php"; // redirect otomatis
        };
    </script>
</head>
<body>
</body>
</html>