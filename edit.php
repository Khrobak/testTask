<?php
require_once 'src/Config.php';
require_once 'src/Request.php';
require_once 'src/Database.php';

use src\Request;

$requests = new Request([$_REQUEST]);
$db = Database::getDBO();
$user = $db->getRowById('users', $_SESSION['id']);
$users = $db->getFieldsByWhere('login, phone, email', 'users', '`id`!=?', [$_SESSION['id']]);
function getArrayFromUsers(array $users, string $field): array
{
    $array = [];
    foreach ($users as $user) {
        $array[] = $user[$field];
    }
    return $array;
}


$title = 'Редактирование';
$content = "edit";
$fields = ['name', 'login', 'phone', 'email', 'password'];
$insert_values = [];
if (isset($requests->edit)) {
    if (!empty($requests->name) &&
        !empty($requests->login) &&
        !empty($requests->phone) &&
        !empty($requests->email) &&
        !empty($requests->password)
        && !empty($requests->password_confirmed)) {
        $_SESSION['errors'] = [];
        if (in_array($requests->login, getArrayFromUsers($users, 'login'))) {
            $_SESSION['errors'][] = 'Пользователь с таким логином уже существует. Придумайте другой.';
        }
        if (preg_match('/^[0-9]{10}/', $requests->phone) !== 1) {
            $_SESSION['errors'][] = 'Номер должен содержать 10 цифр (без восьмерки)';
        }
        if (in_array($requests->phone, getArrayFromUsers($users, 'phone'))) {
            $_SESSION['errors'][] = 'Пользователь с таким номером телефона уже существует.';
        }
        if (filter_var($requests->email, FILTER_VALIDATE_EMAIL) === false) {
            $_SESSION['errors'][] = 'Введен некорректный адрес почты.';
        }
        if (in_array($requests->email, getArrayFromUsers($users, 'email'))) {
            $_SESSION['errors'][] = 'Пользователь с таким адресом почты уже существует.';
        }
        if ($requests->password !== $requests->password_confirmed) {
            $_SESSION['errors'][] = 'Пароли в полях не совпадают.';
        }
        $insert_values[] = $requests->name;
        $insert_values[] = $requests->login;
        $insert_values[] = $requests->phone;
        $insert_values[] = $requests->email;
        $insert_values[] = md5($requests->password);
    } else {
        $_SESSION['errors'][] = 'Все поля должны быть заполнены';
    }
    if (empty($_SESSION['errors'])) {
        try {
            if ($db->updateById('users', $fields, $insert_values, $_SESSION['id'])) {
                $_SESSION['message'] = 'Данные успешно изменены';
            }
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }
}


require_once 'src/exit.php';
require_once "html/main.php";
