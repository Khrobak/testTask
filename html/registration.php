<?php if (!isset($_SESSION['message'])) { ?>
    <h2>Форма регистрации </h2>
    <form action='' method='post'>

        <p><label>Имя </label> <input type='text' name='name'/></p>
        <p><label>Логин </label> <input type='text' name='login'/></p>
        <p><label>Почта </label> <input type='text' name='email'/></p>
        <p><label>Телефон </label> <input type='tel' name='phone'/></p>
        <p><label>Пароль </label> <input type='password' name='password'/></p>
        <p><label>Повторите пароль </label> <input type='password' name='password_confirmed'/></p>
        <input type='submit' name='reg' value='Зарегестрироваться'/>
    </form>
    <?php if (!empty($_SESSION['errors'])) { ?>
        <ul>
            <?php foreach ($_SESSION['errors'] as $error) { ?>
                <li style="color: brown">  <?= $error; ?> </li> <?php } ?>
        </ul>
    <?php }
    unset($_SESSION['errors']);
} else { ?>
    <p>
        <?= $_SESSION['message'] ?>
    </p>
    <?php unset($_SESSION['message']);
}
?>




