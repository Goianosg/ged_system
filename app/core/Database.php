<?php
class Database {
    // Propriedades de conexão
    private $host = DB_HOST; private $user = DB_USER; private $pass = DB_PASS; private $dbname = DB_NAME;
    private $dbh; private $stmt;
    public function __construct(){
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = [ PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ ];
        try{ $this->dbh = new PDO($dsn, $this->user, $this->pass, $options); } catch(PDOException $e){ die($e->getMessage()); }
    }
    // Retorna o número de linhas afetadas pela última instrução SQL
    public function rowCount(){
    return $this->stmt->rowCount();
}
    public function query($sql){ $this->stmt = $this->dbh->prepare($sql); }
    public function bind($param, $value, $type = null){
        if(is_null($type)){ switch(true){ case is_int($value): $type = PDO::PARAM_INT; break; case is_bool($value): $type = PDO::PARAM_BOOL; break; case is_null($value): $type = PDO::PARAM_NULL; break; default: $type = PDO::PARAM_STR; } }
        $this->stmt->bindValue($param, $value, $type);
    }
    // Executa a instrução preparada
    public function execute(){ return $this->stmt->execute(); }
    public function resultSet(){ $this->execute(); return $this->stmt->fetchAll(); }
    public function single(){ $this->execute(); return $this->stmt->fetch(); }
    // Retorna o último ID inserido
    public function lastInsertId(){
      return $this->dbh->lastInsertId();
    }
}