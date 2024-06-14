<?php
include("../components/auth.php");
include("../components/header.php");

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>生徒一覧</title>
</head>

<body>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            <h1>生徒一覧</h1>
            <div>
                <a href="../crud/create/create_student.php" class="btn btn-primary">生徒を追加する</a>
                <a href="../index.php" class="btn btn-dark">戻る</a>
            </div>
        </header>

        <?php
        if (isset($_SESSION['msg'])) {
            echo "<div class='alert alert-success'>{$_SESSION['msg']}</div>";
            unset($_SESSION['msg']);
        }
        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>苗字</th>
                    <th>名前</th>
                    <th>年齢</th>
                    <th>性別</th>
                    <th>誕生日</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("../conf/connect.php");
                $stmt = $conn->prepare("SELECT * FROM students");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $gender_display = ($row["gender"] == "male") ? "男性" : (($row["gender"] == "female") ? "女性" : "不明");
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row["age"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($gender_display, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row["birthday"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <a href="index_test.php?student_id=<?php echo $row["id"] ?>" class="btn btn-success">成績を見る</a>
                            <a href="../crud/edit/edit_student.php?id=<?php echo $row["id"] ?>" class="btn btn-warning">編集する</a>
                            <a href="../crud/delete/delete_student.php?id=<?php echo $row["id"] ?>" class="btn btn-danger" onclick="return confirm('本当にこの生徒情報を削除しますか？')">削除する</a>
                        </td>
                    </tr>
                <?php
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>