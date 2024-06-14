<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>成績登録画面</title>
</head>

<body>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            <h1>成績情報</h1>
            <div>
                <a href="../../index_student.php" class="btn btn-outline-primary">戻る</a>
            </div>
        </header>

        <?php
        $student_id = intval($_GET['student_id']); // URL パラメータから student_id を取得
        ?>

        <form action="../../conf/server.php" method="post" onsubmit="return validateScores()">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">

            <div class="form-element my-4">
                <input class="form-control" type="date" name="test_date" placeholder="テスト日" required>
            </div>
            <div class="form-element my-4">
                <select name="test_type_name" class="form-control" required>
                    <option value="">テストの種類</option>
                    <option value="前期中間">前期中間</option>
                    <option value="前期期末">前期期末</option>
                    <option value="後期中間">後期中間</option>
                    <option value="後期期末">後期期末</option>
                </select>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="english" id="english" placeholder="英語の点数" min="0" max="100" required>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="japanese" id="japanese" placeholder="国語の点数" min="0" max="100" required>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="math" id="math" placeholder="数学の点数" min="0" max="100" required>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="social" id="social" placeholder="社会の点数" min="0" max="100" required>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="science" id="science" placeholder="理科の点数" min="0" max="100" required>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="text" id="total" placeholder="合計点" readonly>
            </div>
            <div class="form-element">
                <input class="btn btn-primary" type="submit" name="create_test" value="登録する">
            </div>
        </form>
    </div>

    <script src="../../main.js"></script>
</body>

</html>