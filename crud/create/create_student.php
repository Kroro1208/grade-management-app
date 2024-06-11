<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>生徒登録画面</title>
</head>

<body>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            <h1>生徒情報</h1>
            <div>
                <a href="../../index_student.php" class="btn btn-outline-primary">戻る</a>
            </div>
        </header>
        <form action="../../conf/server.php" method="post">
            <div class="form-element my-4">
                <input class="form-control" type="text" name="last_name" placeholder="苗字">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="text" name="first_name" placeholder="名前">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="age" placeholder="年齢">
            </div>
            <div class="form-element my-4">
                <select name="gender" class="form-control">
                    <option value="">性別</option>
                    <option value="male">男性</option>
                    <option value="female">女性</option>
                </select>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="date" name="birthday" placeholder="誕生日">
            </div>
            <div class="form-element">
                <input class="btn btn-primary" type="submit" name="create" value="登録する">
            </div>
        </form>
    </div>
</body>

</html>