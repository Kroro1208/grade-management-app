<?php
session_start(); // セッションを開始

include(__DIR__ . "/connect.php");

// 生徒情報の登録処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $class_id = $_POST['class_id'];

    $sql = "INSERT INTO students (last_name, first_name, age, gender, birthday, class_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $last_name, $first_name, $age, $gender, $birthday, $class_id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "生徒情報が登録されました。";
        header("Location: ../pages/index_student.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}


// 生徒情報の更新処理
if (isset($_POST["update"])) {
    $student_id = intval($_POST["student_id"]);
    $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
    $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
    $age = mysqli_real_escape_string($conn, $_POST["age"]);
    $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
    $birthday = mysqli_real_escape_string($conn, $_POST["birthday"]);

    // プリペアドステートメントを使用
    $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, age = ?, gender = ?, birthday = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $first_name, $last_name, $age, $gender, $birthday, $student_id);

    if ($stmt->execute()) {
        $_SESSION["msg"] = "生徒情報の更新が成功しました";
    } else {
        $_SESSION["msg"] = "エラーが発生しました: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../index_student.php");
    exit();
}

// テストと成績の登録処理
if (isset($_POST["create_test"])) {
    $student_id = intval($_POST["student_id"]);
    $test_date = mysqli_real_escape_string($conn, $_POST["test_date"]);
    $test_type_name = mysqli_real_escape_string($conn, $_POST["test_type_name"]);
    $english = isset($_POST["english"]) ? intval($_POST["english"]) : null;
    $japanese = isset($_POST["japanese"]) ? intval($_POST["japanese"]) : null;
    $math = isset($_POST["math"]) ? intval($_POST["math"]) : null;
    $social = isset($_POST["social"]) ? intval($_POST["social"]) : null;
    $science = isset($_POST["science"]) ? intval($_POST["science"]) : null;

    // test_types テーブルからテスト種類のIDを取得または挿入
    $stmt = $conn->prepare("SELECT id FROM test_types WHERE name = ?");
    $stmt->bind_param("s", $test_type_name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($test_type_id);
        $stmt->fetch();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO test_types (name) VALUES (?)");
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

// 成績情報の更新処理
if (isset($_POST["update_test"])) {
    $test_id = intval($_POST["test_id"]);
    $test_date = mysqli_real_escape_string($conn, $_POST["test_date"]);
    $test_type_name = mysqli_real_escape_string($conn, $_POST["test_type_name"]);
    $english = isset($_POST["english"]) ? intval($_POST["english"]) : null;
    $japanese = isset($_POST["japanese"]) ? intval($_POST["japanese"]) : null;
    $math = isset($_POST["math"]) ? intval($_POST["math"]) : null;
    $social = isset($_POST["social"]) ? intval($_POST["social"]) : null;
    $science = isset($_POST["science"]) ? intval($_POST["science"]) : null;

    // test_types テーブルからテスト種類のIDを取得または挿入
    $stmt = $conn->prepare("SELECT id FROM test_types WHERE name = ?");
    $stmt->bind_param("s", $test_type_name);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($test_type_id);
        $stmt->fetch();
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO test_types (name) VALUES (?)");
        $stmt->bind_param("s", $test_type_name);
        $stmt->execute();
        $test_type_id = $stmt->insert_id;
    }
    $stmt->close();

    // テスト情報を更新
    $stmt = $conn->prepare("UPDATE tests SET test_type_id = ?, test_date = ? WHERE id = ?");
    $stmt->bind_param("isi", $test_type_id, $test_date, $test_id);
    if ($stmt->execute()) {
        // subjects テーブルの成績を更新
        $stmt = $conn->prepare("UPDATE subjects SET english = ?, japanese = ?, math = ?, social = ?, science = ? WHERE test_id = ?");
        $stmt->bind_param("iiiiii", $english, $japanese, $math, $social, $science, $test_id);
        if ($stmt->execute()) {
            $_SESSION["msg"] = "成績の更新が成功しました";
        } else {
            $_SESSION["msg"] = "成績の更新に失敗しました: " . $stmt->error;
        }
    } else {
        $_SESSION["msg"] = "テストの更新に失敗しました: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ../index_student.php");
    exit();
}
