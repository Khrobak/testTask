<?php
require_once 'src/config.php';
require_once 'src/Request.php';
require_once 'src/Database.php';
require_once 'src/RegValidator.php';

use src\Request;

$requests = new Request([$_REQUEST]);
$db = Database::getDBO();
$validator = new RegValidator($requests);
$title = 'Регистрация';
$content = "registration";
$fields = ['name', 'login', 'phone', 'email', 'password'];
$insert_values = [];
if (isset($requests->reg)) {

    if (!$validator->valuesHaveEmpty()) {
        $validator->validate();
        $insert_values = $validator->getInsertValues();
    } else {
        $_SESSION['errors'][] = 'Все поля должны быть заполнены';
    }

    if (empty($_SESSION['errors'])) {
        try {
            if ($db->insert('users', $fields, $insert_values) == true) {
                $_SESSION['message'] = 'Регистрация прошла успешно';
            }
        } catch (PDOException $e) {
            print "Error: " . $e->getMessage();
        }
    }

}

require_once 'src/exit.php';
require_once "html/main.php";
