<?php

declare(strict_types=1);

/**
 * ЗАДАНИЕ 7 (часть 2): Страница регистрации с капчей
 * 
 * Требования:
 * 1. Форма с заголовком "Регистрация".
 * 2. Динамическое изображение капчи.
 * 3. Проверка ввода на стороне сервера через сессии.
 * 4. Вывод ответов "Правильно" или "Неверный код".
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

$message = '';
$statusClass = '';

// Функция генерации случайного кода для капчи (5-6 символов)
function generateCaptchaCode(int $length = 5): string {
    $chars = '23456789abcdefghjkmnpqrstuvwxyz'; // Исключены похожие символы (1, l, 0, o)
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

// Инициализация кода капчи при первом посещении
if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode(random_int(5, 6));
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $input = trim($_POST['captcha_input']);
    $expected = $_SESSION['captcha_code'] ?? '';
    
    if ($input !== '' && $expected !== '' && strtolower($input) === strtolower($expected)) {
        $message = 'Правильно';
        $statusClass = 'success';
    } else {
        $message = 'Неверный код';
        $statusClass = 'error';
    }
    
    // Обновление кода после каждой попытки
    $_SESSION['captcha_code'] = generateCaptchaCode(random_int(5, 6));
}

// Принудительное обновление через GET
if (isset($_GET['refresh'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode(random_int(5, 6));
    header('Location: task_07.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        body { font-family: "Times New Roman", serif; background: #fff; margin: 50px; }
        h1 { font-size: 48px; margin-bottom: 30px; }
        .captcha-container { margin-bottom: 20px; }
        .captcha-image { border: 1px solid #ccc; display: block; margin-bottom: 10px; }
        .input-group { font-size: 24px; display: flex; align-items: center; gap: 10px; }
        input[type="text"] { font-size: 24px; padding: 5px; width: 150px; border: 1px solid #777; }
        button { font-size: 20px; padding: 5px 15px; margin-top: 10px; cursor: pointer; }
        .result-message { font-size: 32px; margin-top: 30px; }
        .result-message.success { color: #000; } /* На скриншоте "Правильно" черного цвета */
        .result-message.error { color: red; }
        .refresh-link { font-size: 16px; color: blue; text-decoration: underline; cursor: pointer; display: block; margin-top: 5px; }
    </style>
</head>
<body>
    <h1>Регистрация</h1>

    <div class="captcha-container">
        <img src="task_07_gen.php?t=<?= time() ?>" alt="Капча" class="captcha-image">
        <a href="task_07.php?refresh=1" class="refresh-link">Обновить картинку</a>
    </div>

    <form method="POST">
        <div class="input-group">
            <label for="captcha_input">Введите строку</label>
            <input type="text" id="captcha_input" name="captcha_input" autocomplete="off" required>
        </div>
        <button type="submit">OK</button>
    </form>

    <?php if ($message !== ''): ?>
        <div class="result-message <?= $statusClass ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

</body>
</html>
