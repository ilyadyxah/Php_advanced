<?php

use Add\Exceptions\NotFoundException;
use Add\Services\ReplaceService;

$text = 'Товарищи! сложившаяся структура организации представляет собой интересный эксперимент проверки направлений прогрессивного развития. Значимость этих проблем настолько очевидна, что консультация с широким активом играет важную роль в формировании новых предложений.
Товарищи! консультация с широким активом позволяет выполнять важные задания по разработке систем массового участия. Товарищи! сложившаяся структура организации представляет собой интересный эксперимент проверки направлений прогрессивного развития. Значимость этих проблем настолько очевидна, что консультация с широким активом играет важную роль в формировании новых предложений.
Равным образом рамки и место обучения кадров влечет за собой процесс внедрения и модернизации системы обучения кадров, соответствует насущным потребностям. Равным образом консультация с широким активом требуют определения и уточнения модели развития. С другой стороны рамки и место обучения кадров способствует подготовки и реализации модели развития.';

$argv = ['index.php', 'search=место', 'replace=place'];

try {
    if (count($argv) < 2) {
        throw new NotFoundException('404');
    }

    $replaceService = new ReplaceService($argv, $text);
    echo $replaceService->replace();

} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
    http_response_code(404);
}