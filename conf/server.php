<?php
session_start(); // セッションを開始

include(__DIR__ . "/connect.php");

if (isset($_POST["create"])) {
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $age = mysqli_real_escape_string($conn, $_POST["age"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $birthday = mysqli_real_escape_string($conn, $_POST["birthday"]);

    // プリペアドステートメントを使用
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, age, gender, birthday) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $first_name, $last_name, $age, $gender, $birthday);

    if ($stmt->execute()) {
        $_SESSION["msg"] = "生徒情報の登録が成功しました";
    } else {
        $_SESSION["msg"] = "エラーが発生しました: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../index_student.php");
    exit();
}
