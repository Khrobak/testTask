<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content_Type" content="text/html; charset=utf-8">
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <title><?= $title ?></title>
</head>
<body>
<div style="float:left;">
    <?php if (isset($_SESSION['login'])) { ?> <h3>Здравствуйте, <?= $_SESSION['login'] ?> </h3> <?php } ?>
    <ul>
        <li><a href='index.php'> Главная</a></li>
        <?php if (!empty($_SESSION['login'])) { ?>
            <li><a href='edit.php'>Редактировать профиль </a></li>
            <li>
                <form method='post' action=''><input type='submit' name='del' value='Выйти'/></form>
            </li><?php } else { ?>
            <li><p><a href='authorization.php'> Авторизация</a></p></li>
            <li><p><a href='registration.php'> Регистрация</a></p></li> <?php } ?>
    </ul>

</div>


<div style="margin-left:300px;"> <?php require_once "html/$content.php" ?></div>
</body>
</html>