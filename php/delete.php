<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "DELETE FROM patients WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() > 0) {
            header("Location: ../index.php?status=success");
            exit;
        } else {
            header("Location: ../index.php?status=notfound");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: ../index.php?status=error");
        exit;
    }
} else {
    header("Location: ../index.php?status=noid");
    exit;
}
?>
