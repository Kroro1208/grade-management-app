<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>生徒登録画面</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            margin-bottom: 20px;
        }

        .form-element {
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <header class="d-flex justify-content-between align-items-center">
            <h1 class="h3">生徒情報</h1>
            <div>
                <a href="../../index_student.php" class="btn btn-outline-primary">戻る</a>
            </div>
        </header>
        <form action="../../conf/server.php" method="post">
            <div class="form-element">
                <label for="last_name" class="form-label">苗字</label>
                <input class="form-control" type="text" name="last_name" id="last_name" placeholder="苗字">
            </div>
            <div class="form-element">
                <label for="first_name" class="form-label">名前</label>
                <input class="form-control" type="text" name="first_name" id="first_name" placeholder="名前">
            </div>
            <div class="form-element">
                <label for="age" class="form-label">年齢</label>
                <input class="form-control" type="number" name="age" id="age" placeholder="年齢">
            </div>
            <div class="form-element">
                <label for="gender" class="form-label">性別</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">性別を選択</option>
                    <option value="male">男性</option>
                    <option value="female">女性</option>
                </select>
            </div>
            <div class="form-element">
                <label for="birthday" class="form-label">誕生日</label>
                <input class="form-control" type="date" name="birthday" id="birthday">
            </div>
            <div class="form-element">
                <input class="btn btn-primary w-100" type="submit" name="create" value="登録する">
            </div>
        </form>
    </div>
</body>

</html>