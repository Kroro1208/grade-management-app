<?php
include("../components/auth.php");
include("../components/header.php");

// データベース接続とクエリの実行
include("../conf/connect.php");

if (!isset($_GET['student_id'])) {
    echo "<div class='alert alert-danger'>生徒IDが指定されていません。</div>";
    exit();
}

$student_id = intval($_GET['student_id']);

$stmt = $conn->prepare("
    SELECT t.id AS test_id, tt.name AS test_type, t.test_date, s.english, s.japanese, s.math, s.social, s.science, s.id AS subject_id,
           (s.english + s.japanese + s.math + s.social + s.science) AS total
    FROM subjects s
    JOIN tests t ON s.test_id = t.id
    JOIN test_types tt ON t.test_type_id = tt.id
    WHERE s.student_id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>成績一覧</title>
</head>

<body>
    <div class="container">
        <header class="my-4">
            <h1 class="text-center">成績一覧</h1>
        </header>

        <?php
        // 生徒情報を取得
        $stmt_student = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt_student->bind_param("i", $student_id);
        $stmt_student->execute();
        $student_result = $stmt_student->get_result();
        $student = $student_result->fetch_assoc();
        ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title mb-0"><?php echo htmlspecialchars($student["last_name"] . " " . $student["first_name"], ENT_QUOTES, 'UTF-8'); ?> の成績</h2>
                <div>
                    <a href="index_student.php" class="btn btn-outline-primary">戻る</a>
                    <a href="../crud/create/create_test.php?student_id=<?php echo $student_id; ?>" class="btn btn-primary">成績を追加する</a>
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
                            <th>アクション</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $test_types = [];
                        $test_dates = [];
                        $scores = [
                            "english" => [],
                            "japanese" => [],
                            "math" => [],
                            "social" => [],
                            "science" => []
                        ];
                        while ($row = $result->fetch_assoc()) {
                            $test_types[] = $row["test_type"];
                            $test_dates[] = $row["test_date"];
                            $scores["english"][] = $row["english"];
                            $scores["japanese"][] = $row["japanese"];
                            $scores["math"][] = $row["math"];
                            $scores["social"][] = $row["social"];
                            $scores["science"][] = $row["science"];
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
                                    <a href="../crud/edit/edit_test.php?test_id=<?php echo $row["test_id"]; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-warning">編集する</a>
                                    <a href="../crud/delete/delete_test.php?id=<?php echo $row["subject_id"]; ?>&student_id=<?php echo $student_id; ?>" class="btn btn-danger" onclick="return confirm('本当にこの成績情報を削除しますか？')">削除する</a>
                                </td>
                            </tr>
                        <?php
                        }
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>

                <div>
                    <canvas id="barChart"></canvas>
                </div>
                <div>
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        const testTypes = <?php echo json_encode($test_types); ?>;
        const testDates = <?php echo json_encode($test_dates); ?>;
        const scores = <?php echo json_encode($scores); ?>;

        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: testDates,
                datasets: [{
                        label: '英語',
                        data: scores.english,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '国語',
                        data: scores.japanese,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '数学',
                        data: scores.math,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '社会',
                        data: scores.social,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '理科',
                        data: scores.science,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const radarCtx = document.getElementById('radarChart').getContext('2d');
        const radarChart = new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: testTypes,
                datasets: [{
                        label: '英語',
                        data: scores.english,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '国語',
                        data: scores.japanese,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '数学',
                        data: scores.math,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '社会',
                        data: scores.social,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '理科',
                        data: scores.science,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    r: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>