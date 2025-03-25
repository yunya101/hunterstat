<?php
if (isset($_GET['title']) && isset($_GET['keywords'])) {
    echo '<div id="searched">';
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
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'User-Agent: hunterstatistic/1.0 (egoryunev@yandex.ru)'
    ]);

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

    $jsons = array();
    $vacancies = array();
    for ($i = 0; $i < count($responses); $i++) {
        $json = json_decode($responses[$i], true);
        if (count($json['items']) == 0 || !isset($json['items'])) {
            break;
        }
        $vacancies[$i] = $json['items'];
    }

    echo '<span>Всего вакансий: ' . $json['found'] . ' | </span>';
    $selected = 0;

    foreach ($vacancies as $index => $response) {
        foreach ($response as $j => $vacancy) {
            $selected++;
            $snippet = $vacancy['snippet'];

            foreach ($snippet as $key => $val) {
                $val = mb_strtolower($val, 'UTF-8');
                foreach ($keyword_count as $keyword => $count) {
                    if (strpos($val, $keyword) !== false) {
                        $keyword_count[$keyword] = $count + 1;
                    }
                }
            }
        }
    }

    echo '<span>Выборка из ' . $selected . ' вакансий</span><br>';

    foreach ($keyword_count as $key => $count) {
        echo '<p class="result">' . $key . ': ' . $count . '</p>';
    }
    echo '</div>';
}

function sanitize($str): string
{
    return strip_tags($str);
}