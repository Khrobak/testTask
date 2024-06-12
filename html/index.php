<h1>Главная страница </h1>
<p> Информацию на главной странице видят все пользователи </p>
<?php if (empty($_SESSION['login'])) { ?>
<p><a href='authorization.php'> Авторизация</a></p>
<p><a href='registration.php'> Регистрация</a></p>
<?php } ?>