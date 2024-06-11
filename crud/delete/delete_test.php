<?php
session_start();
include("../../conf/connect.php");

if (isset($_GET['id'])) {
    $score_id = intval($_GET['id']);

    // 成績情報を削除
    $stmt = $conn->prepare("DELETE FROM subjects WHERE id = ?");
    $stmt->bind_param("i", $score_id);
    if ($stmt->execute()) {
        $_SESSION["msg"] = "成績情報が削除されました";
    } else {
        $_SESSION["msg"] = "成績情報の削除に失敗しました: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: ../../index_student.php");
exit();
