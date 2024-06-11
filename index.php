<?php
// データベース接続
include("conf/connect.php");

// 生徒の成績を合計し、ランキング順に取得するクエリ
$query = "
    SELECT 
        s.id, 
        s.first_name, 
        s.last_name, 
        s.age, 
        s.gender, 
        s.birthday, 
        COALESCE(SUM(sb.english + sb.japanese + sb.math + sb.social + sb.science), 0) AS total_score 
    FROM students s
    LEFT JOIN subjects sb ON s.id = sb.student_id
    GROUP BY s.id
    ORDER BY total_score DESC";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>成績管理アプリ</title>
    <style>
        body {
            background-color: gray;
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
                成績管理アプリ
            </div>
            <div class="card-body text-center">
                <a href="index_student.php" class="btn btn-primary btn-custom btn-primary-custom">生徒一覧へ</a>
                <h2 class="mt-4">成績ランキング</h2>
                <table class="table table-striped table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>順位</th>
                            <th>苗字</th>
                            <th>名前</th>
                            <th>年齢</th>
                            <th>性別</th>
                            <th>誕生日</th>
                            <th>合計得点</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        while ($row = $result->fetch_assoc()) {
                            $gender_display = ($row["gender"] == "male") ? "男性" : (($row["gender"] == "female") ? "女性" : "不明");
                        ?>
                            <tr>
                                <td><?php echo $rank++; ?></td>
                                <td><?php echo htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["age"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($gender_display, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["birthday"], ENT_QUOTES, 'UTF-8'); ?></td>
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