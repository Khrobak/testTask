<?php

require_once 'src/config.php';
require_once 'src/Request.php';
require_once 'src/Database.php';

use src\Request;

$db = Database::getDBO();

$title = 'Test';
$content = "index";


require_once 'src/exit.php';
require_once "html/main.php";

