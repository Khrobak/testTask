<?php

abstract class AValidator
{
    protected $values;
    protected $users;

    function __construct($requests)
    {
        $this->values['name'] = $requests->name;
        $this->values['login'] = $requests->login;
        $this->values['phone'] = $requests->phone;
        $this->values['email'] = $requests->email;
        $this->values['password'] = md5($requests->password);
        $this->values['password_confirmed'] = md5($requests->password_confirmed);
        $this->getUsers();

    }

    public function valuesHaveEmpty(): bool
    {
        return in_array(null, $this->values);
    }

    public function validate()
    {
        $_SESSION['errors'] = [];
        if (in_array($this->values['login'], $this->getFieldFromUsers('login'))) {
            $_SESSION['errors'][] = 'Пользователь с таким логином уже существует. Придумайте другой.';
        }
        if (preg_match('/^[0-9]{10}/', $this->values['phone']) !== 1) {
            $_SESSION['errors'][] = 'Номер должен содержать 10 цифр (без восьмерки)';
        }
        if (in_array($this->values['phone'], $this->getFieldFromUsers('phone'))) {
            $_SESSION['errors'][] = 'Пользователь с таким номером телефона уже существует.';
        }
        if (filter_var($this->values['email'], FILTER_VALIDATE_EMAIL) === false) {
            $_SESSION['errors'][] = 'Введен некорректный адрес почты.';
        }
        if (in_array($this->values['email'], $this->getFieldFromUsers('email'))) {
            $_SESSION['errors'][] = 'Пользователь с таким адресом почты уже существует.';
        }
        if ($this->values['password'] !== $this->values['password_confirmed']) {
            $_SESSION['errors'][] = 'Пароли в полях не совпадают.';
        }
    }

    protected function getFieldFromUsers(string $field): array
    {
        $array = [];
        foreach ($this->users as $user) {
            $array[] = $user[$field];
        }
        return $array;
    }

    public function getInsertValues(): array
    {
        unset($this->values['password_confirmed']);
        foreach ($this->values as $value) {
            $array[] = $value;
        }
        return $array;
    }

    abstract protected function getUsers(): void;
}