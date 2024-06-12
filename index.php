<?php

require_once 'src/Config.php';
require_once 'src/Request.php';
require_once 'src/Database.php';
use src\Request;

$db = Database::getDBO();

$title = 'Test';
$content = "index";

require_once 'authorization.php';
require_once 'registration.php';
require_once 'src/exit.php';
require_once "html/main.php";

