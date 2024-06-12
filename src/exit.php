<?php
if (isset($requests->del)) {
    unset($_SESSION['login'], $_SESSION['id']);
    header('Location:http://localhost:8000/index.php');
    exit;
}
?>