<?php

require_once 'Config.php'; 

class Database {
private $pdo;
private static $db;

private function __construct() {
    try {
        $this->pdo=new PDO( 'mysql:host='.DB_HOST.'; dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    } catch(PDOEXception $e) {
        echo 'Ошибка при подключении к БД: '.$e->getMessage();
    }
}
public static function getDBO() {
    if (!self::$db) self::$db = new Database();
    return self::$db;
}

public function getCountRows(string $table_name, string $where ='', array $values=[]) :int {
    $query = 'SELECT count(*) FROM `'. $table_name. '`';
    if (!empty($where)) $query .= "WHERE $where";
    $query = $this->pdo->prepare($query);
    if (!empty($values))$query->execute($values);
    $query-> execute();
    return $query->fetchColumn();
}

public function getRows(string $table_name, string $where = '', array $values= [], string $order_by ='') :array {
$query = 'SELECT * FROM '. $table_name;
if (!empty($where)) $query .= " WHERE $where";
if (!empty($order_by)) $query .= "ORDER BY $order_by";
$query = $this->pdo->prepare($query);
   if (!empty($values)) $query->execute($values);
   $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

public function getFieldsByWhere(string $fields, string $table_name, string $where, array $values) :array {
    $query = "SELECT $fields FROM ". $table_name. " WHERE $where";
    $query = $this->pdo->prepare($query);
    $query->execute($values);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if (isset($result)) return $result;
    return [];
}
    public function getRowByWhere(string $table_name, string $where, array $values) :mixed {
        $query = 'SELECT * FROM '. $table_name. " WHERE $where";
        $query = $this->pdo->prepare($query);
        $query->execute($values);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (is_array($result)) return $result;
        return 'user not found';
    }

    public function getFields(string $fields, string $table_name) {
        $query = "SELECT $fields FROM ". "$table_name";
        $query = $this->pdo->prepare($query);
        $query->execute();
        $result = $query->fetchAll();
        if (isset($result)) return $result;
        return [];
    }

public function getRowById(string $table_name, int $id ) :array|bool {
     return $this->getRowByWhere($table_name, ' `id`=? ', [$id]);
}

 public function updateById(string $table_name, array $fields, array $values, int $id) {
     $values[] = $id;
     $query = 'UPDATE '. $table_name. ' SET ';
     foreach ($fields as $field) { $query .= " $field = ?,";}
     $query = substr($query, 0, -1);
     $query = $query.'WHERE `id` = ?';
     $query = $this->pdo->prepare($query);
     $query->execute($values);
     return 'updated';
 }

 
 public function insert(string $table_name, array $fields, array $insert_values) {
     $query = 'INSERT '. "`$table_name`". ' (';
     foreach ($fields as $field) { $query .= "$field, ";}
     $query = substr($query, 0, -2);
     $query = $query.') VALUES ( ';
     foreach ($insert_values as $value) { $query .="?, ";}
     $query = substr($query, 0, -2);
     $query =$query. ');';
     
     $query = $this->pdo->prepare($query);
     return $query->execute($insert_values);
 }
 
 public function __destruct() {
     $this->pdo = null;
 }
}

?>