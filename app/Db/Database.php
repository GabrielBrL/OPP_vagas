<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Database
{

    /**
     * Host do banco de dados
     * @var string
     */
    const HOST = 'localhost';

    /**
     * Nome do Database
     * @var string
     */
    const DBNAME = 'wdev_vagas';

    /**
     * Senha do banco
     * @var string
     */
    const USER = 'root';

    /**
     * Senha do banco
     * @var string
     */
    const PASSWORD = 'Gabriel3456';

    /**
     * Nome da tabela
     * @var string
     */
    private $table;

    /**
     * Instancia de conexão PDO
     * @var PDO
     */
    private $conn;

    /**
     * Define a tabela, instancia e conexão
     * @param string $table
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Método responsável por criar a conexão com o banco de dados
     */
    private function setConnection()
    {
        try {
            $this->conn = new PDO('mysql:host=' . self::HOST . ';dbname=' . self::DBNAME, self::USER, self::PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            die("ERROR: " . $error->getMessage());
        }
    }

    /**
     * Método responsável por executar query's dentro do banco de dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->conn->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $error) {
            die('ERROR: ' . $error->getMessage());
        }
    }

    /**
     * Método responsável por inserir os dados no banco
     * @param array $values [ field => value ]
     * @return integer ID inserido
     */
    public function insert($values)
    {
        $fields = array_keys($values);
        $binds  = array_pad([], count($fields), '?');

        //MONTA A QUERY
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        //EXECUTA O INSERT
        $this->execute($query, array_values($values));

        //RETORNA O ID INSERIDO
        return $this->conn->lastInsertId();
    }

    /**
     * Método responsável por executar uma consulta no banco de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {

        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'WHERE ' . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        return $this->execute($query);
    }

    /**
     * Método responsável por atualizar o banco de dados
     * @param string $where
     * @param array $values [ field => value ]
     * @return boolean
     */
    public function update($where, $values)
    {

        $fields = array_keys($values);

        $query = 'UPDATE ' . $this->table . ' SET ' . implode("=?,", $fields) . '=? WHERE ' . $where;

        $this->execute($query, array_values($values));
    }

    /**
     * Método responsavel por excluir do banco de dados
     */
    public function delete($where)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        echo "<pre>"; print_r($query);echo"</pre>";
        $this->execute($query);
//        return true;
    }
}
