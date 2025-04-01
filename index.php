<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="icon" type="image/png" sizes="16x16"  href="assets/favicon-16x16.png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Hunter Statistic</title>
</head>

<body>
    <div id="header">
        <h1>Hunter Statistic</h1>
        <span id="info">
            <a id="git" href="https://github.com/yunya101/hunterstat"><img class="icon" src="assets/github.png"></a>
            <p>Beta 1.0</p>
        </span>
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
                <div id="selectors">
                    <label>Опыт работы:</label>
                    <select name="exp" class="select">
                        <option value="none">Любой</option>
                        <option value="noExperience">Без опыта</option>
                        <option value="between1And3">От 1 до 3 лет</option>
                        <option value="between3And6">От 3 до 6 лет</option>
                        <option value="moreThan6">Более 6 лет</option>
                    </select>
                    <label>Выборка</label>
                    <select name="select" class="select">
                        <option value="true">Только с названием в заголовке</option>
                        <option value="false">Классическая</option>
                    </select>

                </div>
                <button type="submit">Найти</button>
            </form>
        </div>

        <?php
        require_once 'search.php';
        ?>
        <script>
            $('.links').hide();
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

            $('.drop_button').click(function (e) {
                e.stopPropagation();

                const linksDiv = $(this).closest('.result').next('.links');

                linksDiv.toggle();
                $(this).html(function (_, html) {
                    return html.includes('▼') ? html.replace('▼', '▲') : html.replace('▲', '▼');
                });
            });
        </script>
    </div>
</body>

</html>