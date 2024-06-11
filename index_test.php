<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>成績一覧</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-top: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .btn-outline-primary {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="my-4">
            <h1 class="text-center">成績一覧</h1>
        </header>

        <?php
        include("conf/connect.php");
        $student_id = intval($_GET['student_id']);

        // 生徒情報を取得
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $student_result = $stmt->get_result();
        $student = $student_result->fetch_assoc();
        ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title mb-0"><?php echo htmlspecialchars($student["last_name"] . " " . $student["first_name"], ENT_QUOTES, 'UTF-8'); ?> の成績</h2>
                <div>
                    <a href="index_student.php" class="btn btn-outline-primary">戻る</a>
                    <a href="crud/create/create_test.php?student_id=<?php echo $student_id; ?>" class="btn btn-primary">成績を追加する</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>テスト種類</th>
                            <th>日付</th>
                            <th>英語</th>
                            <th>国語</th>
                            <th>数学</th>
                            <th>社会</th>
                            <th>理科</th>
                            <th>合計</th>
                            <th>アクション</th> <!-- 編集ボタン用の列を追加 -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // 成績を取得
                        $stmt = $conn->prepare("
                            SELECT t.id AS test_id, tt.name AS test_type, t.test_date, s.english, s.japanese, s.math, s.social, s.science,
                                   (s.english + s.japanese + s.math + s.social + s.science) AS total
                            FROM subjects s
                            JOIN tests t ON s.test_id = t.id
                            JOIN testtypes tt ON t.test_type_id = tt.id
                            WHERE s.student_id = ?
                        ");
                        $stmt->bind_param("i", $student_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["test_type"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["test_date"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["english"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["japanese"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["math"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["social"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["science"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["total"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="crud/edit/edit_test.php?test_id=<?php echo $row["test_id"]; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-warning">編集する</a>
                                    <a href="crud/edit/edit_test.php?test_id=<?php echo $row["test_id"]; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-danger">削除する</a>
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
        </div>
    </div>
</body>

</html>