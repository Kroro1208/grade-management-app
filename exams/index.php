<?php
session_start();
// データベース接続
include("../components/header.php");
include("../conf/connect.php");

$user_type = $_SESSION['user_type'];
$teacher_id = $_SESSION['user_id'];

// テスト結果を取得するクエリ
$query = "
    SELECT 
        s.id AS student_id, 
        CONCAT(s.last_name, ' ', s.first_name) AS student_name, 
        c.grade, 
        c.class_number, 
        tt.name AS test_type, 
        sub.english, 
        sub.math, 
        sub.science, 
        sub.social, 
        sub.japanese, 
        (sub.english + sub.math + sub.science + sub.social + sub.japanese) AS total_score
    FROM subjects sub
    JOIN students s ON sub.student_id = s.id
    JOIN classes c ON s.class_id = c.id
    JOIN tests t ON sub.test_id = t.id
    JOIN test_types tt ON t.test_type_id = tt.id
";

// 先生の種類に応じてクエリを追加
if ($user_type === 'class_teacher') {
    $query .= " WHERE c.id IN (SELECT class_id FROM teachers WHERE id = ?)";
} elseif ($user_type === 'grade_head') {
    $query .= " WHERE c.grade IN (SELECT grade FROM grade_assignments WHERE teacher_id = ?)";
}

$query .= " ORDER BY total_score DESC"; // 合計点数で並び替え

$stmt = $conn->prepare($query);

if ($user_type === 'class_teacher' || $user_type === 'grade_head') {
    $stmt->bind_param("i", $teacher_id);
}

$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesomeのリンクを追加 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            width: 1/2;
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
            border-radius: 10px;
            overflow: hidden;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }

        th {
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        th:hover {
            color: #007bff;
        }

        th .sort-icon {
            display: inline-block;
            margin-left: 5px;
            vertical-align: middle;
            opacity: 0.5;
        }

        th.sorted-asc .sort-icon {
            opacity: 1;
        }

        th.sorted-desc .sort-icon {
            opacity: 1;
            transform: rotate(180deg);
        }

        .ranking-icon {
            margin-right: 10px;
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
                <a href="download.php" class="btn btn-primary btn-custom btn-primary-custom">CSVダウンロード</a>
                <table class="table table-striped table-hover mt-3" id="resultsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ランク</th>
                            <th onclick="sortTable(1, true)">学生番号<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(2)">テスト<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(3)">学年とクラス<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(4)">名前<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(5, true)">英語<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(6, true)">数学<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(7, true)">理科<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(8, true)">社会<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(9, true)">国語<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(10, true)">合計<i class="bi bi-caret-down-fill sort-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($students as $index => $row) {
                            $rank = $index + 1;
                            $ranking_icon = '';
                            if ($rank == 1) {
                                $ranking_icon = '<i class="fa-solid fa-1 ranking-icon"></i>';
                            } elseif ($rank == 2) {
                                $ranking_icon = '<i class="fa-solid fa-2 ranking-icon"></i>';
                            } elseif ($rank == 3) {
                                $ranking_icon = '<i class="fa-solid fa-3 ranking-icon"></i>';
                            }
                        ?>
                            <tr>
                                <td><?php echo $ranking_icon; ?></td>
                                <td><?php echo htmlspecialchars($row["student_id"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["test_type"], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row["grade"], ENT_QUOTES, 'UTF-8') . "年 " . htmlspecialchars($row["class_number"], ENT_QUOTES, 'UTF-8') . "クラス"; ?></td>
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
    <script src="../main.js"></script>
</body>

</html>

<?php
$conn->close();
?>
