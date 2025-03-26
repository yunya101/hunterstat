<?php
if (isset($_GET['title']) && isset($_GET['keywords'])) {
    $title = sanitize($_GET['title']);
    $str_keys = sanitize($_GET['keywords']);
    $keywords = explode(' ', $str_keys);
    if ($keywords == false) {
        echo '<span class="err" style="display: block">Ошибка парсинга keywords</span>';
        exit;
    }

    $keyword_count = array();

    for ($i = 0; $i < count($keywords); $i++) {
        $keyword_count[strtolower($keywords[$i])] = 0;
    }

    $data = [
        'text' => $title,
        'per_page' => 100
    ];

    if (isset($_GET['exp']) && $_GET['exp'] !== 'none') {
        $data['experience'] = $_GET['exp'];
    }

    $curl = set_curl();
    $responses = array();
    for ($i = 0; $i < 20; $i++) {
        $url = 'https://api.hh.ru/vacancies?' . http_build_query($data) . '&page=' . $i;

        curl_setopt($curl, CURLOPT_URL, $url);
        $response = curl_exec($curl);
        if ($response == false) {
            break;
        }

        $responses[$i] = $response;
    }

    curl_close($curl);

    $items = array();
    for ($i = 0; $i < count($responses); $i++) {
        $json = json_decode($responses[$i], true);
        if (count($json['items']) == 0 || !isset($json['items'])) {
            break;
        }
        $items[$i] = $json['items'];
    }


    $count_vacancies = 0;

    foreach ($items as $i => $item) {
        foreach ($item as $j => $vacancy) {
            $name = mb_strtolower($vacancy['name'], 'UTF-8');

            if ($_GET['select'] == 'true' && strpos($name, $title) === false) {
                continue;
            }
            $count_vacancies++;
            $requirement = mb_strtolower($vacancy['snippet']['requirement'], 'UTF-8');
            foreach ($keyword_count as $keyword => $count) {
                if (strpos($requirement, $keyword) !== false) {
                    $keyword_count[$keyword] = $count + 1;
                }
            }
        }
    }
    echo '<div id="searched">';
    echo "<h3>Статистика по вакансии: $title</h3>";
    echo '<p>Всего вакансий найденных hh: ' . $json['found'] . '</p>';
    echo "<p>Выборка из $count_vacancies вакансий</p>";

    foreach ($keyword_count as $key => $count) {
        echo '<p class="result">' . $key . ': ' . $count . '</p>';
    }
    echo '</div>';
}

function sanitize($str): string
{
    return strip_tags($str);
}

function set_curl()
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'User-Agent: hunterstatistic/1.0 (egoryunev@yandex.ru)'
    ]);

    return $curl;
}