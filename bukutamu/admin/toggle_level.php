<?php
include '../koneksi.php';

if (isset($_GET['id']) && isset($_GET['level'])) {
    $id = intval($_GET['id']);
    $new_level = ($_GET['level'] === 'on') ? 'on' : 'off';

    $query = "UPDATE admin SET level = '$new_level' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: t_admin.php");
        exit;
    } else {
        echo "Gagal mengubah level: " . mysqli_error($conn);
    }
} else {
    echo "Data tidak valid.";
}
?>