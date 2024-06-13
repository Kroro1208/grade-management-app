<?php
session_start();
include("conf/connect.php");

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_type = $_POST['user_type'];
    $grade = $_POST['grade'] ?? null; // 学年は選択された場合のみ取得
    $class_number = $_POST['class_id'] ?? null; // クラス番号は選択された場合のみ取得
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // クラス情報の登録（class_teacherまたはgrade_headの場合）
        if ($user_type === 'class_teacher' || $user_type === 'grade_head') {
            $sql_class = "INSERT INTO classes (name, grade, class_number, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
            $stmt_class = $conn->prepare($sql_class);
            $class_name = "{$grade}年 {$class_number}クラス";
            $stmt_class->bind_param("sss", $class_name, $grade, $class_number);
            if ($stmt_class->execute()) {
                $class_id = $conn->insert_id; // 追加されたクラスのIDを取得
            } else {
                $error_message = 'Error creating class: ' . $stmt_class->error;
                $stmt_class->close();
                exit();
            }
            $stmt_class->close();
        } else {
            $class_id = null; // 校長の場合はクラスIDをnullに設定
        }

        // 先生情報の登録
        $sql_teacher = "INSERT INTO teachers (first_name, last_name, user_type, class_id, password, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt_teacher = $conn->prepare($sql_teacher);
        $stmt_teacher->bind_param("sssis", $first_name, $last_name, $user_type, $class_id, $hashed_password);

        if ($stmt_teacher->execute()) {
            $_SESSION['success_message'] = 'アカウントが作成されました';
            header("Location: index.php");
            exit();
        } else {
            $error_message = 'Error creating account: ' . $stmt_teacher->error;
        }
        $stmt_teacher->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Account</title>
    <script src="main.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center my-4">アカウント登録画面</h2>
                <?php if ($error_message) : ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if ($success_message) : ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <form method="post" action="create_account.php">
                    <div class="form-group mb-3">
                        <label for="first_name">苗字</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="last_name">名前</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="user_type">あなたの役職</label>
                        <select class="form-control" id="user_type" name="user_type" required>
                            <option value="">担当役職を選択してください</option>
                            <option value="class_teacher">一般</option>
                            <option value="grade_head">学年主任</option>
                            <option value="principal">校長</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="grade_field" style="display: none;">
                        <label for="grade">担当学年</label>
                        <select class="form-control" id="grade" name="grade">
                            <option value="">学年を選択してください</option>
                            <option value="1">1年</option>
                            <option value="2">2年</option>
                            <option value="3">3年</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="class_field" style="display: none;">
                        <label for="class_id">担当クラス</label>
                        <select class="form-control" id="class_id" name="class_id">
                            <option value="">クラスを選択してください</option>
                            <option value="1">クラス1</option>
                            <option value="2">クラス2</option>
                            <option value="3">クラス3</option>
                            <option value="4">クラス4</option>
                            <option value="5">クラス5</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">パスワード</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirm_password">パスワード確認</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">アカウント登録</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>