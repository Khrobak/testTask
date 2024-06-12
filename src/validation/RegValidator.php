<?php

require_once 'AValidator.php';
require_once '../Database.php';

class RegValidator extends AValidator
{
    protected function getUsers(): void
    {
        $db = Database::getDBO();
        $this->users = $db->getFields('login, phone, email', 'users');
    }
}