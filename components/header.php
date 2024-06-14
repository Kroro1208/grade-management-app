<?php

$user_name = $_SESSION['user_name'] ?? '';
$user_type = $_SESSION['user_type'] ?? '';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>成績管理アプリ</title>
    <style>
        .navbar-text {
            margin-right: 20px;
        }
        .btn-logout {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">成績管理アプリ</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <?php if ($user_name && $user_type) : ?>
                        <li class="nav-item">
                            <span class="navbar-text text-white">
                                <?php echo htmlspecialchars("$user_name ($user_type)", ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-danger btn-logout" href="/grade-management/logout.php">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>
