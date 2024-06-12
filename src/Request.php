<?php

namespace src;
require_once 'config.php';

class Request
{
    private array $data = [];

    public function __construct()
    {
        $this->xss($_REQUEST);
    }

    public function xss(array|string $x)
    {
        if (is_array($x)) {
            foreach ($x as $k => &$v) {
                if (!is_array($v)) $this->data[$k] = trim(htmlspecialchars($v));
                else $this->xss($v);
            }
        }

    }

    public function __get(string $a): mixed
    {
        if (isset($this->data[$a])) return $this->data[$a];
        return false;
    }

    public function __isset(string $name): bool
    {
        if (isset($this->data[$name])) return true;
        return false;
    }


}
