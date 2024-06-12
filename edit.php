<?php
require_once 'src/config.php';
require_once 'src/Request.php';
require_once 'src/Database.php';
require_once 'src/validation/EditValidator.php';

use src\Request;

$requests = new Request([$_REQUEST]);
$db = Database::getDBO();
$validator = new EditValidator($requests);
$user = $db->getRowById('users', $_SESSION['id']);

$title = 'Редактирование';
$content = "edit";
$fields = ['name', 'login', 'phone', 'email', 'password'];
$insert_values = [];
if (isset($requests->edit)) {
    if (!$validator->valuesHaveEmpty()) {
        $validator->validate();
        $insert_values = $validator->getInsertValues();
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
