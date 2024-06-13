// 先生の役職によって選択肢を制限する処理
document.addEventListener('DOMContentLoaded', function () {
    const userType = document.getElementById('user_type');
    if (userType) {
        userType.addEventListener('change', toggleFields);
        toggleFields(); // 初期状態を設定
    }

    function toggleFields() {
        const userTypeValue = userType.value;
        const gradeField = document.getElementById('grade_field');
        const classField = document.getElementById('class_field');

        if (userTypeValue === 'class_teacher') {
            gradeField.style.display = 'block';
            classField.style.display = 'block';
        } else if (userTypeValue === 'grade_head') {
            gradeField.style.display = 'block';
            classField.style.display = 'none';
        } else {
            gradeField.style.display = 'none';
            classField.style.display = 'none';
        }
    }
});
