<?php
  /*
   * Classe de Banco de Dados PDO
   * Conecta ao banco de dados
   * Cria prepared statements
   * Binds
   * Retorna linhas e resultados
   */
  class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
      // Seta DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );

      // Cria instância do PDO
      try{
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }

    // Prepara statement com a query
    public function query($sql){
      $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind de valores
    public function bind($param, $value, $type = null){
      if(is_null($type)){
        switch(true){
          case is_int($value):
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value):
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $type = PDO::PARAM_NULL;
            break;
          default:
            $type = PDO::PARAM_STR;
        }
      }

      $this->stmt->bindValue($param, $value, $type);
    }

    // Executa o prepared statement
    public function execute(){
      return $this->stmt->execute();
    }

    // Retorna o resultado em um array de objetos
    public function resultSet(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Retorna um único registro como objeto
    public function single(){
      $this->execute();
      return $this->stmt->fetch(PDO_FETCH_OBJ);
    }

    // Retorna a contagem de linhas
    public function rowCount(){
      return $this->stmt->rowCount();
    }

    // Retorna o último ID inserido
    public function lastInsertId(){
      return $this->dbh->lastInsertId();
    }
  }