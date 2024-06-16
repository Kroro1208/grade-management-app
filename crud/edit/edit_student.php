<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>生徒編集画面</title>
</head>

<body>
    <div class="container">
        <header class="d-flex justify-content-between my-4">
            <h1>生徒情報編集</h1>
            <div>
                <a href="../../index_student.php" class="btn btn-outline-primary">戻る</a>
            </div>
        </header>

        <?php
        session_start();
        include("../../conf/connect.php");
        $student_id = intval($_GET['id']);
        $user_type = $_SESSION['user_type'];
        $teacher_id = $_SESSION['user_id'];

        // 生徒情報を取得
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();

        // クラス情報を取得
        $class_id = $student['class_id'];
        $stmt = $conn->prepare("SELECT grade, class_number FROM classes WHERE id = ?");
        $stmt->bind_param("i", $class_id);
        $stmt->execute();
        $stmt->bind_result($grade, $class_number);
        $stmt->fetch();
        $stmt->close();

        // 担当学年を取得
        $grades = [];
        if ($user_type === 'grade_head') {
            $stmt = $conn->prepare("SELECT grade FROM grade_assignments WHERE teacher_id = ?");
            $stmt->bind_param("i", $teacher_id);
            $stmt->execute();
            $stmt->bind_result($assigned_grade);
            while ($stmt->fetch()) {
                $grades[] = $assigned_grade;
            }
            $stmt->close();
        }
        $conn->close();
        ?>

        <form action="../../conf/server.php" method="post">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            <div class="form-element my-4">
                <input class="form-control" type="text" name="last_name" value="<?php echo htmlspecialchars($student['last_name'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="苗字">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="text" name="first_name" value="<?php echo htmlspecialchars($student['first_name'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="名前">
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="number" name="age" value="<?php echo htmlspecialchars($student['age'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="年齢">
            </div>
            <div class="form-element my-4">
                <select name="gender" class="form-control">
                    <option value="">性別</option>
                    <option value="male" <?php if ($student['gender'] == 'male') echo 'selected'; ?>>男性</option>
                    <option value="female" <?php if ($student['gender'] == 'female') echo 'selected'; ?>>女性</option>
                </select>
            </div>
            <div class="form-element my-4">
                <input class="form-control" type="date" name="birthday" value="<?php echo htmlspecialchars($student['birthday'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="誕生日">
            </div>
            <div class="form-element my-4">
                <label for="class_id" class="form-label">クラス</label>
                <select name="class_id" id="class_id" class="form-control" required>
                    <option value="">クラスを選択してください</option>
                    <?php
                    if ($user_type === 'grade_head') {
                        foreach ($grades as $assigned_grade) {
                            for ($i = 1; $i <= 5; $i++) {
                                $selected = ($grade == $assigned_grade && $class_number == $i) ? 'selected' : '';
                                echo "<option value='{$assigned_grade}-{$i}' {$selected}>{$assigned_grade}年 {$i}クラス</option>";
                            }
                        }
                    } elseif ($user_type === 'principal') {
                        for ($g = 1; $g <= 3; $g++) {
                            for ($cn = 1; $cn <= 5; $cn++) {
                                $selected = ($grade == $g && $class_number == $cn) ? 'selected' : '';
                                echo "<option value='{$g}-{$cn}' {$selected}>{$g}年 {$cn}クラス</option>";
                            }
                        }
                    } else {
                        // class_teacherの場合のクラス情報
                        echo "<option value='{$grade}-{$class_number}' selected>{$grade}年 {$class_number}クラス</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-element">
                <input class="btn btn-primary" type="submit" name="update" value="更新する">
            </div>
        </form>
    </div>
    <script src="../../main.js" defer></script>
</body>

</html>