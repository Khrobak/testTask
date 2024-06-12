<?php if (!isset($_SESSION['message'])) { ?>
    <h2>Форма редактирования личной информации </h2>
    <form action='' method='post'>

        <p><label>Имя </label> <input type='text' name='name' value="<?= $user['name'] ?>"/></p>
        <p><label>Логин </label> <input type='text' name='login' value="<?= $user['login'] ?>"/></p>
        <p><label>Почта </label> <input type='text' name='email' value="<?= $user['email'] ?>"/></p>
        <p><label>Телефон </label> <input type='tel' name='phone' value="<?= $user['phone'] ?>"/></p>
        <p><label>Пароль </label> <input type='password' name='password'/></p>
        <p><label>Повторите пароль </label> <input type='password' name='password_confirmed'/></p>
        <input type='submit' name='edit' value='Изменить'/>
    </form>
    <?php if (!empty($_SESSION['errors'])) { ?>
        <p>
        <ul>
            <?php foreach ($_SESSION['errors'] as $error) { ?>
                <li style="color: brown">  <?= $error; ?> </li> <?php } ?>
        </ul>
        </p>
    <?php }
    unset($_SESSION['errors']);
} else { ?>
    <p>
        <?= $_SESSION['message'] ?>
    </p>
    <?php unset($_SESSION['message']);
}
?>




