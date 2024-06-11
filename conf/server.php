<?php
session_start(); // セッションを開始

include(__DIR__ . "/connect.php");

// 生徒情報の登録処理
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


// テストと成績の登録処理
// if (isset($_POST["create_test"])) {
//     $student_id = intval($_POST["student_id"]);
//     $test_date = mysqli_real_escape_string($conn, $_POST["test_date"]);
//     $subject_name = mysqli_real_escape_string($conn, $_POST["subject_name"]);
//     $test_type_name = mysqli_real_escape_string($conn, $_POST["test_type_name"]);
//     $score = intval($_POST["score"]);

//     // subjects テーブルに科目を追加または取得
//     $stmt = $conn->prepare("SELECT id FROM subjects WHERE name = ?");
//     $stmt->bind_param("s", $subject_name);
//     $stmt->execute();
//     $stmt->store_result();
//     if ($stmt->num_rows > 0) {
//         $stmt->bind_result($subject_id);
//         $stmt->fetch();
//     } else {
//         $stmt->close();
//         $stmt = $conn->prepare("INSERT INTO subjects (name) VALUES (?)");
//         $stmt->bind_param("s", $subject_name);
//         $stmt->execute();
//         $subject_id = $stmt->insert_id;
//     }
//     $stmt->close();

//     // testtypes テーブルにテストの種類を追加または取得
//     $stmt = $conn->prepare("SELECT id FROM testtypes WHERE name = ?");
//     $stmt->bind_param("s", $test_type_name);
//     $stmt->execute();
//     $stmt->store_result();
//     if ($stmt->num_rows > 0) {
//         $stmt->bind_result($test_type_id);
//         $stmt->fetch();
//     } else {
//         $stmt->close();
//         $stmt = $conn->prepare("INSERT INTO testtypes (name) VALUES (?)");
//         $stmt->bind_param("s", $test_type_name);
//         $stmt->execute();
//         $test_type_id = $stmt->insert_id;
//     }
//     $stmt->close();

//     // テストを作成
//     $stmt = $conn->prepare("INSERT INTO tests (test_type_id, subject_id, test_date) VALUES (?, ?, ?)");
//     $stmt->bind_param("iis", $test_type_id, $subject_id, $test_date);

//     if ($stmt->execute()) {
//         $test_id = $stmt->insert_id; // 作成されたテストのIDを取得

//         // 成績を作成
//         $stmt = $conn->prepare("INSERT INTO scores (student_id, test_id, score) VALUES (?, ?, ?)");
//         $stmt->bind_param("iii", $student_id, $test_id, $score);

//         if ($stmt->execute()) {
//             $_SESSION["msg"] = "成績の登録が成功しました";
//         } else {
//             $_SESSION["msg"] = "成績の登録に失敗しました: " . $stmt->error;
//         }
//     } else {
//         $_SESSION["msg"] = "テストの登録に失敗しました: " . $stmt->error;
//     }

//     $stmt->close();
//     $conn->close();

//     header("Location: ../index_student.php");
//     exit();
// }


if (isset($_POST["create_test"])) {
    $student_id = intval($_POST["student_id"]);
    $test_date = mysqli_real_escape_string($conn, $_POST["test_date"]);
    $test_type_name = mysqli_real_escape_string($conn, $_POST["test_type_name"]);
    $english = isset($_POST["english"]) ? intval($_POST["english"]) : null;
    $japanese = isset($_POST["japanese"]) ? intval($_POST["japanese"]) : null;
    $math = isset($_POST["math"]) ? intval($_POST["math"]) : null;
    $social = isset($_POST["social"]) ? intval($_POST["social"]) : null;
    $science = isset($_POST["science"]) ? intval($_POST["science"]) : null;

    // testtypes テーブルからテスト種類のIDを取得または挿入
    $stmt = $conn->prepare("SELECT id FROM testtypes WHERE name = ?");
    $stmt->bind_param("s", $test_type_name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($test_type_id);
        $stmt->fetch();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO testtypes (name) VALUES (?)");
        $stmt->bind_param("s", $test_type_name);
        $stmt->execute();
        $test_type_id = $stmt->insert_id;
    }
    $stmt->close();

    // テストを作成
    $stmt = $conn->prepare("INSERT INTO tests (test_type_id, test_date) VALUES (?, ?)");
    $stmt->bind_param("is", $test_type_id, $test_date);

    if ($stmt->execute()) {
        $test_id = $stmt->insert_id; // 作成されたテストのIDを取得

        // subjects テーブルに点数を挿入
        $stmt = $conn->prepare("INSERT INTO subjects (test_id, student_id, english, japanese, math, social, science) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiiiii", $test_id, $student_id, $english, $japanese, $math, $social, $science);

        if ($stmt->execute()) {
            $_SESSION["msg"] = "成績の登録が成功しました";
        } else {
            $_SESSION["msg"] = "成績の登録に失敗しました: " . $stmt->error;
        }
    } else {
        $_SESSION["msg"] = "テストの登録に失敗しました: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../index_student.php");
    exit();
}
