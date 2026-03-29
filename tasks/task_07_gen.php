<?php

declare(strict_types=1);

/**
 * ЗАДАНИЕ 7 (часть 1): Продвинутая генерация капчи
 * 
 * Требования:
 * 1. Символы разных цветов (один обязательно красный).
 * 2. Символы разного размера (18-30 пт).
 * 3. Расстояние между символами ~40 пт.
 * 4. Графические шумы: точки и линии разного цвета и размера.
 * 5. Использование TrueType шрифтов из C:\Windows\Fonts.
 * 
 * @category Tasks
 * @package  PHP_Tasks
 * @author   Kantemir
 * @license  MIT
 * @date     2026-03-29
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Генерирует изображение капчи с продвинутыми эффектами.
 *
 * @return void
 */
function generateCaptchaImage(): void
{
    if (!extension_loaded('gd')) {
        header('Content-Type: image/png');
        echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
        return;
    }

    $width = 300;
    $height = 100;
    $img = imagecreatetruecolor($width, $height);

    if ($img === false) {
        return;
    }

    // Цвета
    $white = (int)imagecolorallocate($img, 255, 255, 255);
    $red = (int)imagecolorallocate($img, 255, 0, 0);
    
    // Заливка фона
    imagefill($img, 0, 0, $white);

    // Получение кода
    $code = $_SESSION['captcha_code'] ?? 'ERROR';
    $length = strlen($code);

    // Путь к шрифту
    $fontPath = 'C:\\Windows\\Fonts\\arial.ttf';
    if (!file_exists($fontPath)) {
        $fontPath = 'C:\\Windows\\Fonts\\arial.ttf'; // Запасной вариант
    }

    // 1. Рисуем символы (разного размера, цвета, один красный)
    $redIndex = random_int(0, $length - 1);
    $startX = 20;

    for ($i = 0; $i < $length; $i++) {
        $size = random_int(18, 30);
        $angle = random_int(-15, 15);
        
        if ($i === $redIndex) {
            $color = $red;
        } else {
            $color = (int)imagecolorallocate($img, random_int(0, 150), random_int(0, 150), random_int(0, 150));
        }

        $y = random_int(45, 75);
        
        // Отрисовка символа
        imagettftext($img, (float)$size, (float)$angle, $startX, $y, $color, $fontPath, $code[$i]);
        
        $startX += 40; // Расстояние между символами
    }

    // 2. Наложение шумов (линии разного цвета и толщины)
    for ($i = 0; $i < 8; $i++) {
        $lineColor = (int)imagecolorallocate($img, random_int(100, 220), random_int(100, 220), random_int(100, 220));
        imagesetthickness($img, random_int(1, 2));
        imageline($img, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $lineColor);
    }

    // 3. Наложение шумов (точки разного цвета и размера)
    for ($i = 0; $i < 1000; $i++) {
        $dotColor = (int)imagecolorallocate($img, random_int(50, 255), random_int(50, 255), random_int(50, 255));
        imagesetpixel($img, random_int(0, $width), random_int(0, $height), $dotColor);
    }

    // Вывод
    header('Content-Type: image/png');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    imagepng($img);
    imagedestroy($img);
}

// Запуск
generateCaptchaImage();
