<?php
session_start();
include("conf/connect.php");

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_type = $_POST['user_type'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO teachers (first_name, last_name, user_type, password, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $first_name, $last_name, $user_type, $hashed_password);

        if ($stmt->execute()) {
            // アカウント作成成功後にログイン処理を行う
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_name'] = $first_name . ' ' . $last_name;
            $_SESSION['user_type'] = $user_type;
            header("Location: index.php");
            exit();
        } else {
            $error_message = 'エラー発生。再度やりなおしてください。: ' . $stmt->error;
        }
        $stmt->close();
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
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center my-4">アカウント登録画面</h2>
                <?php if ($error_message) : ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
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