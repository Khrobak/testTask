<?php
require_once 'src/Config.php';
require_once 'src/Request.php';
require_once 'src/Database.php';

use src\Request;

define('SMARTCAPTCHA_SERVER_KEY', 'ysc2_uX0cwUEteA64LVjl6Jawzgswv0plFeg0lC8Eppit1f2ac6ae');
function check_captcha($token)
{
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'],
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
}
function validateEmail($request): string|bool
{
    if ( filter_var($request, FILTER_VALIDATE_EMAIL) === false) {
        return "Введен некорректный адрес почты";
    } else {
        return true;
    }
}
function validatePhone($request): string|bool
{
    if ( preg_match('/^[0-9]{10}/', $request) !== 1) {
        return "Номер должен содержать 10 цифр (без восьмерки)";
    } else {
        return true;
    }
}

$requests = new Request([$_REQUEST]);
$db = Database::getDBO();
$title = 'Авторизация';
$content = "authorization2";
if (isset($requests->auth)) {
    $token = $_POST['smart-token'];
    if (check_captcha($token)) {
        $password = md5($requests->auth_password);
        $phoneOrEmail = $requests->auth_phone_or_email;
        if (!empty($password) && !empty($phoneOrEmail)) {
            $type = (preg_match('/^([0-9])+$/', $phoneOrEmail) === 1) ? 'phone' : 'email';
            $resultValidation =  match ($type) {
                'phone' => validatePhone($phoneOrEmail),
                'email' => validateEmail($phoneOrEmail),
            };
            print_r(gettype($resultValidation));
            if ($resultValidation == true) {
                try{
                    $where = '`' . $type . '`=? AND `password`=?';
                    $user_data = $db->getRowByWhere('users', $where, [$phoneOrEmail, $password]);
                    if ($user_data === 'user not found') {
                        $_SESSION['errors'][] = 'Такого пользователя не найдено. Проверьте входные данные.';
                    }
                    if (is_array($user_data)) {
                        $_SESSION['login'] = $user_data['login'];
                        $_SESSION['id'] = $user_data['id'];
                        $_SESSION['message'] = 'Вы успешно авторизованы';
                    }
                } catch (PDOException $e) {
                    print "Error: " . $e->getMessage();
                }
            } else {
                $_SESSION['errors'][] = $resultValidation;
            }
        } else {
            $_SESSION['errors'][] = 'Заполните все поля';
        }
    } else {
        $_SESSION['message'] = 'Вы робот';
    }
}


require_once 'src/exit.php';
require_once "html/main.php";
