<?php
// データベース接続
include("../conf/connect.php");

// テスト結果を取得するクエリ
$query = "
    SELECT 
        s.id AS student_id, 
        CONCAT(s.last_name, ' ', s.first_name) AS student_name, 
        tt.name AS test_type, 
        sub.english, 
        sub.math, 
        sub.science, 
        sub.social, 
        sub.japanese, 
        (sub.english + sub.math + sub.science + sub.social + sub.japanese) AS total_score
    FROM subjects sub
    JOIN students s ON sub.student_id = s.id
    JOIN tests t ON sub.test_id = t.id
    JOIN testtypes tt ON t.test_type_id = tt.id
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>テスト結果一覧</title>
    <style>
        body {
            background-color: #f4f4f9;
            color: #333;
        }

        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .btn-custom {
            width: 100%;
            margin-bottom: 15px;
            padding: 15px;
            font-size: 18px;
            border-radius: 50px;
        }

        .btn-primary-custom {
            background-color: #007bff;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: #0056b3;
        }

        .btn-secondary-custom {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary-custom:hover {
            background-color: #545b62;
        }

        .table {
            margin-top: 20px;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                テスト結果一覧
            </div>
            <div class="card-body text-center">
                <a href="../index.php" class="btn btn-secondary btn-custom btn-secondary-custom">ホームに戻る</a>
                <a href="download.php" class="btn btn-primary btn-custom btn-primary-custom mt-3">CSVダウンロード</a>
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>学生番号</th>
                            <th>テスト</th>
                            <th>名前</th>
                            <th>英語</th>
                            <th>数学</th>
                            <th>理科</th>
                            <th>社会</th>
                            <th>国語</th>
                            <th>合計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["student_id"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["test_type"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["student_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["english"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["math"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["science"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["social"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["japanese"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["total_score"], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>