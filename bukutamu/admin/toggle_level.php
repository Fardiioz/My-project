<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    // Ambil nilai level saat ini
    $query = mysqli_query($conn, "SELECT level FROM admin WHERE id = $id");
    $row = mysqli_fetch_assoc($query);

    if ($row) {
        $current = $row['level'];
        $new_level = ($current === 'admin') ? 'nonaktif' : 'admin';

        // Update
        mysqli_query($conn, "UPDATE admin SET level = '$new_level' WHERE id = $id");
    }
}

header("Location: t_admin.php");
exit;
?>
