<?php

require_once 'Database.php';
require_once 'AValidator.php';

class EditValidator extends AValidator
{
    protected function getUsers(): void
    {
        $db = Database::getDBO();
        $this->users = $db->getFieldsByWhere('login, phone, email', 'users', '`id`!=?', [$_SESSION['id']]);
    }
}