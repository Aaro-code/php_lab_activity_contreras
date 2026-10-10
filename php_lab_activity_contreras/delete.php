<?php
include "dbcon.php";

$id = $_GET['id'] ?? '';

if ($id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: index.php?msg=deleted");
        exit();
    }
    $stmt->close();
}

header("Location: index.php");
exit();
?>