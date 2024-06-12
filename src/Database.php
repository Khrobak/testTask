<?php


require_once 'config.php';

class Database
{
    private $pdo;
    private static $db;

    private function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . DB_HOST . '; dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOEXception $e) {
            echo 'Ошибка при подключении к БД: ' . $e->getMessage();
        }
    }

    public static function getDBO()
    {
        if (!self::$db) self::$db = new Database();
        return self::$db;
    }

    public function getFieldsByWhere(string $fields, string $table_name, string $where, array $values): array
    {
        $query = "SELECT $fields FROM " . $table_name . " WHERE $where";
        $query = $this->pdo->prepare($query);
        $query->execute($values);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (isset($result)) return $result;
        return [];
    }

    public function getRowByWhere(string $table_name, string $where, array $values): mixed
    {
        $query = 'SELECT * FROM ' . $table_name . " WHERE $where";
        $query = $this->pdo->prepare($query);
        $query->execute($values);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (is_array($result)) return $result;
        return 'user not found';
    }

    public function getFields(string $fields, string $table_name): array
    {
        $query = "SELECT $fields FROM " . "$table_name";
        $query = $this->pdo->prepare($query);
        $query->execute();
        $result = $query->fetchAll();
        if (isset($result)) return $result;
        return [];
    }

    public function getRowById(string $table_name, int $id): array|bool
    {
        return $this->getRowByWhere($table_name, ' `id`=? ', [$id]);
    }

    public function updateById(string $table_name, array $fields, array $values, int $id): string
    {
        $values[] = $id;
        $query = 'UPDATE ' . $table_name . ' SET ';
        foreach ($fields as $field) {
            $query .= " $field = ?,";
        }
        $query = substr($query, 0, -1);
        $query = $query . 'WHERE `id` = ?';
        $query = $this->pdo->prepare($query);
        $query->execute($values);
        return 'updated';
    }


    public function insert(string $table_name, array $fields, array $insert_values): bool
    {
        $query = 'INSERT ' . "`$table_name`" . ' (';
        foreach ($fields as $field) {
            $query .= "$field, ";
        }
        $query = substr($query, 0, -2);
        $query = $query . ') VALUES ( ';
        foreach ($insert_values as $value) {
            $query .= "?, ";
        }
        $query = substr($query, 0, -2);
        $query = $query . ');';

        $query = $this->pdo->prepare($query);
        return $query->execute($insert_values);
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}

?>