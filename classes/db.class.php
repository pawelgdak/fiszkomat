<?php

class DB {

    public $db;

    public function __construct() {

        $this->db = new PDO('mysql:host=' . Config::get('sql/host') . ';dbname=' . Config::get('sql/dbname') . '', Config::get('sql/user'), Config::get('sql/password'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    }

    /**
    * Wsadzanie do tabeli
    * $table - nazwa tabeli; $columns - kolumny; $binds - co wsadzamy, np ":foo, :bar";
    * $values - tablica z wartościami bindów, np. ":foo => $foo"
    */

    public function insert($table, $columns, $binds, $values, $multi = false) {

        if(!$multi)
            $sth = $this->db -> prepare('INSERT INTO ' . $table . ' ( ' . $columns . ' ) VALUES (' . $binds . ')');
        else $sth = $this->db -> prepare('INSERT INTO ' . $table . ' ( ' . $columns . ' ) VALUES ' . $binds . '');
        $sth -> execute($values);

        echo "\nPDOStatement::errorInfo():\n";
$arr = $sth->errorInfo();
print_r($arr);

    }

    /**
    * Zwraca ID ostatnio dodanego elementu
    */

    public function lastId() {

        return $this->db->lastInsertId();

    }

    /**
    * Zapytanie zabezpieczone lub zwykłe
    */

    public function query($q, $binds = '') {

        $sth = $this->db->prepare($q);
        $sth->execute($binds);

    }

    /**
    * Wyciąganie z bazy
    * $column - kolumny; $table - nazwa tabeli; $where - opcjonalnie, dodatkowe parametry;
    * $values - jeśli jest coś do zbindowania (np. ":foo => $bar"); 
    * $fetch - sposób zbierania (assoc, one - jeden rekord, false - w ogóle)
    */

    public function select($column, $table, $where = false, $values = '', $fetch = 'assoc') {

        if(!$where)
        $sth = $this->db->prepare('SELECT ' . $column . ' FROM ' . $table);
        else $sth = $this->db->prepare('SELECT ' . $column . ' FROM ' . $table . ' WHERE ' . $where);

        $sth->execute($values);

        if($fetch == 'assoc') return $sth->fetchAll(PDO::FETCH_ASSOC);
        elseif($fetch == 'one') return $sth->fetch(PDO::FETCH_ASSOC);
        elseif($fetch == false) return $sth;

    }

    /**
    * Sprawdza liczbę rekordów w tableli (ogólnie lub z warunkiem - $where)
    */

    public function policz($table, $where = false, $values = '') {

        if(!$where) $sth = $this->db->prepare('SELECT id FROM ' . $table);
        else $sth = $this->db->prepare('SELECT id FROM ' . $table . ' WHERE ' . $where);

        $sth->execute($values);

        return $sth->rowCount();

    }

    public function usun($table, $where, $values) {

        $sth = $this->db->prepare('DELETE FROM ' . $table . ' WHERE ' . $where);
        $sth->execute($values);

    }

    /*
    * Sprawdzanie właściciela
    * np. kategorii lub wyrazów; $table - jaka tabela; $id - id kategorii/wyrazu
    */

    public function checkOwner($table, $id) {

        switch($table) {
            case 'categories':
            case 'words':
                $row = $this->select('user_id', $table, 'id = :id', array(":id"=>$id), 'one');
                return $row['user_id'];
                break;
        }

        return false;

    }

    /*
    * Sprawdzanie czy podany user jest właścicielem
    */

    public function checkIfOwner($table, $id, $uid) {

        $owner = $this->checkOwner($table, $id);
        if($uid==$owner) return true;
        else return false;

    }

    /*
    * Aktualizacja rekordów
    */

    public function update($table, $binds, $values = '', $where = false) {

        if(!$where) 
            $sth = $this->db->prepare('UPDATE ' . $table . ' SET ' . $binds);
        else $sth = $this->db->prepare('UPDATE ' . $table . ' SET ' . $binds . ' WHERE ' . $where);

        $sth->execute($values);

    }
 
  public static function Connect() {
    
    try
    {
        $db = new PDO('mysql:host=' . Config::get('sql/host') . ';dbname=' . Config::get('sql/dbname') . '', Config::get('sql/user'), Config::get('sql/password'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        return $db;
    }
    catch (PDOException $e)
    {
        Alert::Fail('Nie udało połączyć się z bazą');
    }
    
  }
  
}