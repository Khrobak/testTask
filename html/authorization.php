<?php if (!isset($_SESSION['message'])) { ?>
    <h2>Форма авторизации</h2>
    <form action='' method='post'>

        <p><label>Телефон/Почта</label> <input type='text' name='auth_phone_or_email'/></p>
        <p><label>Пароль </label> <input type='password' name='auth_password'/></p>
        <p>
        <div
                style="height: 100px; width: 100px"
                id="captcha-container"
                class="smart-captcha"
                data-sitekey="ysc1_uX0cwUEteA64LVjl6JawYHoWwsXADjIw4WFkurFof62b258d"
        >
            <input type="hidden" name="smart-token">
        </div>
        </p>
        <input type='submit' name='auth' value='Авторизоваться'/>
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




