<?php
    if (isset($_GET['title']) && isset($_GET['keywords'])) {
        echo '<div id="searched">';
        $curl = curl_init();
        $title = $_GET['title'];
        $keywords = explode(' ', $_GET['keywords']);
        if ($keywords == false) {
            //TODO
        }

        $keyword_count = array();

        for ($i = 0; $i < count($keywords); $i++) {
            $keyword_count[strtolower($keywords[$i])] = 0;
        }

        $data = [
            'text' => $title 
        ];

        $url = 'https://api.hh.ru/vacancies?' . 'text=' . $title . '&per_page=100';

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'User-Agent: hunterstatistic/1.0 (egoryunev@yandex.ru)'
        ]);

        $response = curl_exec($curl);
        if ($response == false) {
            // TODO
            echo 'Error';
        }

        $json = json_decode($response, true);
        $vacancies = $json['items'];

        echo '<span>Всего вакансий: ' . $json['found'] . '</span><br>';

        foreach($vacancies as $index => $vacancy) {
            $snippet = $vacancy['snippet'];

            foreach($snippet as $key => $val) {
                $val = mb_strtolower($val, 'UTF-8');
                foreach($keyword_count as $keyword => $count) {
                    if (strpos($val, $keyword) !== false) {
                        $keyword_count[$keyword] = $count + 1;
                    }
                }
            }
        }

        foreach($keyword_count as $key => $count) {
            echo '<p class="result">' . $key . ': ' . $count . '</p>';
        }
        echo '</div>';
    }