<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Hunter Statistic</title>
</head>

<body>
    <div id="header">
        <h1>Hunter Statistic</h1>
        <a id="git" href="https://github.com/yunya101/hunterstat"><img class="icon" src="assets/github.png"></a>
    </div>
    <div id="content">
        <div id="search-container">
            <form action="index.php" method="get" id="form">
                <label>Введите заголовок вакансии</label>
                <div class="err-container">
                    <input type="text" name="title" placeholder="Вакансия" id="form_title">
                    <span id="err_title" class="err">Заголовок вакансии не может быть пустым!</span>
                </div>
                <label>Введите ключевые слова через пробел (регистр не учитывается)</label>
                <div class="err-container">
                    <input type="text" name="keywords" placeholder="Ключевые слова" id="form_keys">
                    <span id="err_keys" class="err">Поле должно содержать хотя бы одно ключевое слово!</span>
                </div>
                <button type="submit">Найти</button>
            </form>
        </div>

        <?php
        require_once 'search.php';
        ?>
        <script>
            $('#form').submit(function () {
                var title = $('#form_title').val()
                var keys = $('#form_keys').val()

                if (title == '') {
                    $('#err_title').css('display', 'block')
                    $('#form_title').css('border-color', 'red')
                    return false
                }

                if (keys == '') {
                    $('#err_keys').css('display', 'block')
                    $('#form_keys').css('border-color', 'red')
                    return false
                }

                return true
            })
        </script>
    </div>
</body>

</html>