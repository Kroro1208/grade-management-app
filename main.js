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

    // ソートするためのイベントリスナーを追加
    const sortableHeaders = document.querySelectorAll("#resultsTable th");
    sortableHeaders.forEach((header, index) => {
        header.addEventListener('click', () => sortTable(index, index >= 3 && index <= 8));
    });
});
