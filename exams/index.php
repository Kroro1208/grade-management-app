<?php
session_start();
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
    JOIN test_types tt ON t.test_type_id = tt.id
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <table class="table table-striped table-hover mt-3" id="resultsTable">
                    <thead class="table-dark">
                        <tr>
                            <th onclick="sortTable(0, true)">学生番号<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(1)">テスト<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(2)">名前<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(3, true)">英語<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(4, true)">数学<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(5, true)">理科<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(6, true)">社会<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(7, true)">国語<i class="bi bi-caret-down-fill sort-icon"></i></th>
                            <th onclick="sortTable(8, true)">合計<i class="bi bi-caret-down-fill sort-icon"></i></th>
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

    <script>
        function sortTable(columnIndex, isNumeric = false) {
            const table = document.getElementById("resultsTable");
            let dir = "asc";
            let rows, i, x, y;
            let sortCount = 0;
            let sorted = true; // ソートが完了したかどうかのフラグ
            let shouldSort = false; // 次の行と比較して並び替えが必要かどうかを判断するフラグ

            // ソートが必要ないか、すべてのソートが完了するまでループ
            while (sorted) {
                sorted = false; // ループの開始時にフラグをリセット
                rows = table.rows;

                // テーブルの各行をループ処理
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSort = false; // ループの開始時にフラグをリセット
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];

                    // 数値か文字列かによって比較方法を変更
                    if (dir === "asc") {
                        if ((isNumeric && parseFloat(x.innerHTML) > parseFloat(y.innerHTML)) ||
                            (!isNumeric && x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase())) {
                            shouldSort = true;
                            break;
                        }
                    } else if (dir === "desc") {
                        if ((isNumeric && parseFloat(x.innerHTML) < parseFloat(y.innerHTML)) ||
                            (!isNumeric && x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase())) {
                            shouldSort = true;
                            break;
                        }
                    }
                }

                // shouldSortフラグがtrueの場合、行を入れ替える
                if (shouldSort) {
                    // table.rowsが<tr>を含むすべての要素
                    // insertBeforeは、親ノードである<tbody>の子ノードの順序を変更する。
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    sorted = true;
                    sortCount++;
                } else {
                    // ソート失敗時
                    if (sortCount === 0 && dir === "asc") {
                        dir = "desc";
                        sorted = true;
                    }
                }
            }
        }
    </script>

</body>

</html>

<?php
$conn->close();
?>