<?php
// デフォルトのタイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "grade-management";

// MySQLi オブジェクトを作成して接続
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// 接続を確認
if ($conn->connect_error) {
    die("接続に失敗しました: " . $conn->connect_error);
}

// 文字セットを設定
if (!$conn->set_charset("utf8mb4")) {
    die("文字セットの設定に失敗しました: " . $conn->error);
}

// デバッグ用に接続成功メッセージを削除
// echo "接続に成功しました！";