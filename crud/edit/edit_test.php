<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>成績編集画面</title>
</head>

<body>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            <h1>成績情報編集</h1>
            <div>
                <a href="../../index_student.php" class="btn btn-outline-primary">戻る</a>
            </div>
        </header>

        <?php
        include("../../conf/connect.php");
        $test_id = intval($_GET['test_id']); // URL パラメータから test_id を取得

        // 成績情報を取得
        $stmt = $conn->prepare("
            SELECT t.test_date, tt.name AS test_type_name, s.english, s.japanese, s.math, s.social, s.science
            FROM tests t
            JOIN test_types tt ON t.test_type_id = tt.id
            JOIN subjects s ON t.id = s.test_id
            WHERE t.id = ?
        ");
        $stmt->bind_param("i", $test_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $test = $result->fetch_assoc();

        $stmt->close();
        $conn->close();
        ?>

        <form action="../../conf/server.php" method="post" oninput="calculateTotal()">
            <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
            <div class="form-element my-4">
                <input class="form-control" type="date" name="test_date" value="<?php echo htmlspecialchars($test['test_date'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-element my-4">
                <select name="test_type_name" class="form-control" required>
                    <option value="">テストの種類</option>
                    <option value="前期中間" <?php if ($test['test_type_name'] == '前期中間') echo 'selected'; ?>>前期中間</option>
                    <option value="前期期末" <?php if ($test['test_type_name'] == '前期期末') echo 'selected'; ?>>前期期末</option>
                    <option value="後期中間" <?php if ($test['test_type_name'] == '後期中間') echo 'selected'; ?>>後期中間</option>
                    <option value="後期期末" <?php if ($test['test_type_name'] == '後期期末') echo 'selected'; ?>>後期期末</option>
                </select>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="english" id="english" value="<?php echo htmlspecialchars($test['english'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="英語の点数">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="japanese" id="japanese" value="<?php echo htmlspecialchars($test['japanese'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="国語の点数">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="math" id="math" value="<?php echo htmlspecialchars($test['math'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="数学の点数">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="social" id="social" value="<?php echo htmlspecialchars($test['social'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="社会の点数">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="science" id="science" value="<?php echo htmlspecialchars($test['science'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="理科の点数">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="text" id="total" placeholder="合計点" readonly>
            </div>
            <div class="form-element">
                <input class="btn btn-primary" type="submit" name="update_test" value="更新する">
            </div>
        </form>
    </div>

    <script>
        function calculateTotal() {
            let english = parseInt(document.getElementById('english').value) || 0;
            let japanese = parseInt(document.getElementById('japanese').value) || 0;
            let math = parseInt(document.getElementById('math').value) || 0;
            let social = parseInt(document.getElementById('social').value) || 0;
            let science = parseInt(document.getElementById('science').value) || 0;
            let total = english + japanese + math + social + science;
            document.getElementById('total').value = total;
        }
    </script>
</body>

</html>