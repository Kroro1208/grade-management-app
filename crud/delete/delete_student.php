<?php
session_start();
include("../../conf/connect.php");

if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);

    // 成績情報を先に削除
    $stmt = $conn->prepare("DELETE FROM subjects WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    if ($stmt->execute()) {
        // 生徒情報を削除
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        if ($stmt->execute()) {
            $_SESSION["msg"] = "生徒情報と成績情報が削除されました";
        } else {
            $_SESSION["msg"] = "生徒情報の削除に失敗しました: " . $stmt->error;
        }
    } else {
        $_SESSION["msg"] = "成績情報の削除に失敗しました: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

header("Location: ../../pages/index_student.php");
exit();
