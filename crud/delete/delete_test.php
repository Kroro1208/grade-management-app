<?php
session_start();
include("../../conf/connect.php");

if (isset($_GET['id']) && isset($_GET['student_id'])) {
    $score_id = intval($_GET['id']);
    $student_id = intval($_GET['student_id']);  // student_id を取得

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

    // student_id をリダイレクトに含める
    header("Location: ../../pages/index_test.php?student_id=" . $student_id);
    exit();
}

// student_id がない場合のエラーハンドリング
$_SESSION["msg"] = "成績情報の削除に失敗しました: 無効なリクエストです。";
header("Location: ../../pages/index_student.php");
exit();
