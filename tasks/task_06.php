<?php

declare(strict_types=1);

/**
 * ЗАДАНИЕ 6: Рисование изображения с помощью GD
 *
 * Описание:
 * Скрипт генерирует PNG-изображение размером 300x150 пикселей.
 * На изображении присутствуют:
 * - Светло-серый фон (235, 235, 240)
 * - Красный прямоугольник (20, 20) — (120, 100)
 * - Синий эллипс с центром (220, 75), шириной 100 и высотой 60
 * - Чёрный текст «PHP» по координатам (130, 65)
 *
 * Соответствует стандартам PSR-12.
 *
 * @category Tasks
 * @package  PHP_Tasks
 * @author   Kantemir
 * @license  MIT
 * @date     2026-03-29
 */

// Начинаем буферизацию вывода, чтобы заголовки отправлялись корректно
ob_start();

/**
 * Генерирует и выводит изображение.
 *
 * @return void
 */
function generateImage(): void
{
    // Проверка наличия расширения GD
    if (!extension_loaded('gd')) {
        handleGdMissing();
        return;
    }

    try {
        // 1. Создание холста (300x150)
        $width = 300;
        $height = 150;
        $img = imagecreatetruecolor($width, $height);

        if ($img === false) {
            throw new Exception("Не удалось создать изображение.");
        }

        // 2. Определение цветов
        // Светло-серый фон (235, 235, 240)
        $bgColor = (int)imagecolorallocate($img, 235, 235, 240);
        // Красный (255, 0, 0)
        $redColor = (int)imagecolorallocate($img, 255, 0, 0);
        // Синий (0, 0, 255)
        $blueColor = (int)imagecolorallocate($img, 0, 0, 255);
        // Чёрный (0, 0, 0)
        $blackColor = (int)imagecolorallocate($img, 0, 0, 0);

        // 3. Рисование
        // Заливка фона
        imagefill($img, 0, 0, $bgColor);

        // Красный залитый прямоугольник (20, 20) — (120, 100)
        imagefilledrectangle($img, 20, 20, 120, 100, $redColor);

        // Синий залитый эллипс с центром (220, 75), шириной 100 и высотой 60
        imagefilledellipse($img, 220, 75, 100, 60, $blueColor);

        // Чёрный текст «PHP» по координатам (130, 65)
        imagestring($img, 5, 130, 65, 'PHP', $blackColor);

        // Зелёный центральный квадрат (увеличен для видимости)
        $greenColor = (int)imagecolorallocate($img, 0, 128, 0);
        $squareSize = 40; // Размер квадрата 40x40 вместо 1x1
        $centerX = (int)($width / 2);
        $centerY = (int)($height / 2);
        imagefilledrectangle(
            $img,
            $centerX - (int)($squareSize / 2),
            $centerY - (int)($squareSize / 2),
            $centerX + (int)($squareSize / 2),
            $centerY + (int)($squareSize / 2),
            $greenColor
        );

        // 4. Вывод результата
        ob_end_clean(); // Очищаем буфер перед отправкой изображения
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        
        if (!imagepng($img)) {
            throw new Exception("Ошибка при генерации PNG.");
        }

        // 5. Освобождение памяти
        imagedestroy($img);
    } catch (Exception $e) {
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('HTTP/1.1 500 Internal Server Error');
        echo "Ошибка: " . $e->getMessage();
    }
}

/**
 * Обработка случая, когда расширение GD отсутствует.
 * Выводит 1x1 прозрачный пиксель.
 *
 * @return void
 */
function handleGdMissing(): void
{
    if (ob_get_length()) {
        ob_end_clean();
    }
    header('Content-Type: image/png');
    header('Cache-Control: no-store');
    // 1x1 прозрачный PNG в base64
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
    exit;
}

// Запуск генерации
generateImage();

// Время завершения выполнения: 2026-03-29 12:00:00 (МСК)
