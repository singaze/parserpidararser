<?php

// URL страницы объекта на Avito
$url = 'https://www.avito.ru/volgograd/kvartiry/3-k._kvartira_58_m_35_et._4322833216';

// Инициализируем cURL
$ch = curl_init($url);

// Устанавливаем заголовки для запроса
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Следовать за редиректами
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/109.0');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'Accept-Language: en-US,en;q=0.5',
    'Connection: keep-alive',
    'Referer: https://www.avito.ru/',
    'Upgrade-Insecure-Requests: 1',
]);

// Выполняем запрос
$html = curl_exec($ch);

// Проверяем на ошибки
if (curl_errno($ch)) {
    echo 'Ошибка запроса: ' . curl_error($ch);
} else {
    // Убедимся, что кодировка UTF-8
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

    // Загружаем HTML в DOMDocument
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    // Ищем элемент с нужным классом
    $finder = new DomXPath($dom);
    $classname = "style-item-address-georeferences-item-TZsrp";
    $nodes = $finder->query("//*[contains(@class, '$classname')]");

    // Извлекаем текстовое содержимое найденного элемента
    if ($nodes->length > 0) {
        // Приведение текста к правильной кодировке
        $district = trim($nodes->item(0)->textContent);
        echo "Район объекта: " . $district . PHP_EOL;
    } else {
        echo "Район не найден.";
    }
}

// Закрываем cURL сессию
curl_close($ch);

?>
