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

    // 点数のバリデーションを追加
    const scoreForm = document.querySelector('form[onsubmit="return validateScores()"]');
    if (scoreForm) {
        scoreForm.addEventListener('submit', validateScores);
    }

    function validateScores(event) {
        const fields = ["english", "japanese", "math", "social", "science"];
        for (let field of fields) {
            let value = parseInt(document.getElementById(field).value);
            if (value < 0 || value > 100) {
                alert("点数は0以上100以下で入力してください。");
                event.preventDefault();
                return false;
            }
        }
        return true;
    }

    // 合計点数を計算する関数
    function calculateTotal() {
        let english = parseInt(document.getElementById('english').value) || 0;
        let japanese = parseInt(document.getElementById('japanese').value) || 0;
        let math = parseInt(document.getElementById('math').value) || 0;
        let social = parseInt(document.getElementById('social').value) || 0;
        let science = parseInt(document.getElementById('science').value) || 0;
        let total = english + japanese + math + social + science;
        document.getElementById('total').value = total;
    }

    // 合計点数の計算をinputイベントで行う
    const scoreFields = document.querySelectorAll("#english, #japanese, #math, #social, #science");
    scoreFields.forEach(field => {
        field.addEventListener('input', calculateTotal);
    });
});
