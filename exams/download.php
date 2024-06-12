<?php
// データベース接続
include("../conf/connect.php");

// ヘッダーの設定
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=exam_results.csv');

// UTF-8のBOMを出力 (Excelの文字化け対策)
echo "\xEF\xBB\xBF";

// 出力バッファを開く
$output = fopen('php://output', 'w');

// CSVの列ヘッダーを出力
fputcsv($output, array('ID', '名前', 'テスト種別', '英語', '数学', '理科', '社会', '国語', '合計'));

// データベースからテスト結果を取得するクエリ
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

// データを1行ずつ取得し、CSVに出力
while ($row = $result->fetch_assoc()) {
    // 各データのエンコーディングをUTF-8に変換
    $row = array_map(function ($value) {
        return mb_convert_encoding($value, 'UTF-8', 'auto');
    }, $row);

    fputcsv($output, $row);
}

// 出力バッファを閉じる
fclose($output);

$conn->close();
exit();
