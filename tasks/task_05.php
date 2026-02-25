<?php
/**
 * ЗАДАНИЕ 5: Форма авторизации с капчей
 *
 * Ваша задача: реализовать вход по логину и паролю с проверкой капчи
 *
 * Что нужно сделать:
 * 1. В начале скрипта вызвать session_start()
 * 2. Реализовать генерацию капчи: при загрузке страницы (или по параметру ?new_captcha=1) генерировать
 *    случайный код (например, 5 цифр), сохранять в $_SESSION['captcha_code'] и выводить на странице
 * 3. Форма: поля login, password, captcha_input и кнопка «Войти»
 * 4. Проверка при POST:
 *    — капча совпадает с $_SESSION['captcha_code'] (после проверки капчу удалить из сессии);
 *    — логин = "admin", пароль = "12345" (для учебного примера можно так; в реальности — хэш пароля и БД)
 * 5. При успешном входе сохранить в сессию факт авторизации (например $_SESSION['user'] = 'admin') и
 *    перенаправить на страницу «Личный кабинет» (ниже — простой вывод «Вы вошли как admin»).
 * 6. На странице «Личный кабинет» проверять сессию; если пользователь не авторизован — перенаправить на форму входа
 * 7. Кнопка «Выйти» — очистить сессию и перенаправить на форму входа
 *
 * Упрощение: логин/пароль можно захардкодить (admin / 12345). Капча — текстовая (код показываем на странице).
 */

// TODO: session_start() в начале

$ошибка = '';
$показать_форму = true;

// Выход
if (isset($_GET['logout'])) {
    // TODO: очистить сессию, session_destroy(), перенаправить на task_05.php
}

// Если уже авторизован и не запрошен выход — показать «кабинет»
if (!empty($_SESSION['user'])) {
    $показать_форму = false;
}

// Генерация капчи (при первой загрузке или по ?new_captcha=1)
if (!isset($_SESSION['captcha_code']) || isset($_GET['new_captcha'])) {
    $код = '';
    for ($i = 0; $i < 5; $i++) {
        $код .= (string)random_int(0, 9);
    }
    $_SESSION['captcha_code'] = $код;
}

// Обработка отправки формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $показать_форму) {
    $логин = trim($_POST['login'] ?? '');
    $пароль = $_POST['password'] ?? '';
    $ввод_капчи = trim($_POST['captcha_input'] ?? '');
    $ожидаемая_капча = $_SESSION['captcha_code'] ?? '';

    // TODO: проверить капчу (сравнить $ввод_капчи и $ожидаемая_капча). После проверки удалить $_SESSION['captcha_code']
    // TODO: если капча неверна — $ошибка = 'Неверный код с картинки'
    // TODO: иначе проверить логин и пароль (admin / 12345). Если неверно — $ошибка = 'Неверный логин или пароль'
    // TODO: если всё верно — записать в $_SESSION['user'] логин, сгенерировать новую капчу на будущее, перенаправить на task_05.php (header + exit)
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 5: Авторизация с капчей</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        .error { color: #c00; margin: 10px 0; }
        .form-block { background: #fff; padding: 20px; border-radius: 8px; margin: 15px 0; }
        label { display: block; margin-top: 12px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; margin-top: 4px; }
        .captcha-code { font-size: 24px; letter-spacing: 4px; padding: 10px; background: #eee; margin: 10px 0; }
        button { margin-top: 15px; padding: 10px 20px; background: #4CAF50; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .btn-logout { background: #c00; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Задание 5: Авторизация с капчей</h1>

    <?php if ($показать_форму): ?>
        <p>Войдите: логин <strong>admin</strong>, пароль <strong>12345</strong>. Введите также код капчи.</p>
        <?php if ($ошибка): ?>
            <p class="error"><?= htmlspecialchars($ошибка) ?></p>
        <?php endif; ?>

        <div class="form-block">
            <p>Код капчи: <span class="captcha-code"><?= htmlspecialchars($_SESSION['captcha_code'] ?? '') ?></span></p>
            <p><a href="task_05.php?new_captcha=1">Обновить капчу</a></p>

            <form method="POST">
                <label>Логин: <input type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" autocomplete="username"></label>
                <label>Пароль: <input type="password" name="password" autocomplete="current-password"></label>
                <label>Код капчи: <input type="text" name="captcha_input" autocomplete="off" maxlength="10"></label>
                <button type="submit">Войти</button>
            </form>
        </div>
    <?php else: ?>
        <div class="form-block">
            <h2>Личный кабинет</h2>
            <p>Вы вошли как <strong><?= htmlspecialchars($_SESSION['user']) ?></strong>.</p>
            <a href="task_05.php?logout=1" class="btn-logout">Выйти</a>
        </div>
    <?php endif; ?>

    <p style="margin-top: 20px;"><a href="task_04.php">← Предыдущее задание</a></p>
</body>
</html>
