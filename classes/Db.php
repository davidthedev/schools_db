<?php

class Db
{
    protected $connection;
    protected $statement;
    protected $fetchStyle;

    public function __construct()
    {
        // dns information goes here
        $dsn = 'mysql:dbname=;host=';
        // db username goes here
        $dbUser = '';
        // db password goes here
        $dbPass = '';

        try {
            $this->connection = new PDO($dsn, $dbUser, $dbPass);
            $this->setFetchStyle('object');
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Prepare a statement for execution
     *
     * @param  string $sql
     * @return object
     */
    protected function prepare($sql)
    {
        try {
            $this->statement = $this->connection->prepare($sql);
            return $this;
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    /**
     * Execute a prepared statement
     *
     * @param  array $bind
     * @return object
     */
    protected function execute(array $bind)
    {
        if (!empty($bind)) {
            foreach ($bind as $column => $value) {
                $this->statement->bindValue($column, $value);
            }
        }

        if (!$this->statement->execute($bind)) {
            $errorMsg = $this->statement->errorInfo();
            throw new Exception($errorMsg[2]);
        }

        return $this;
    }

    /**
     * Prepare and execute a custom query
     *
     * @param  string $query
     * @param  array  $bind
     * @return object
     */
    public function query($query, array $bind = [])
    {
        $this->prepare($query)->execute($bind);
        return $this;
    }

    /**
     * Select data from a table
     *
     * @param  string $table
     * @param  string $columns
     * @param  array  $where
     * @param  string $operator
     * @return object
     */
    public function select($table, $columns = '*', array $where = [], $operator = '')
    {
        $bind = [];

        foreach ($where as $column => $value) {
            unset($where[$column]);
            $where[] = "$column = :$column";
            $bind[':' . $column] = $value;
        }

        $operator = $operator ? $operator : ' AND ';
        $where = implode($operator, $where);

        $sql = "SELECT $columns FROM `$table` ";
        $sql .= $where ? "WHERE $where " : '';

        $this->prepare($sql)->execute($bind);

        return $this;
    }

    /**
     * Insert data into a table
     *
     * @param  string $table
     * @param  array  $bind
     * @return object
     */
    public function insert($table, array $bind)
    {
        $columns = implode('`, `', array_keys($bind));
        $values = implode(', :', array_keys($bind));

        foreach ($bind as $column => $value) {
            unset($bind[$column]);
            $bind[':' . $column] = $value;
        }

        $sql = 'INSERT INTO ' . $table . ' (`' . $columns . '`) ';
        $sql .= 'VALUES (:' . $values . ')';

        return $this->prepare($sql)->execute($bind);
    }

    /**
     * Set fetching style
     *
     * @param string $style
     * @return void
     */
    public function setFetchStyle($style)
    {
        switch ($style) {
            case 'assoc':
                $this->fetchStyle = PDO::FETCH_ASSOC;
                break;
            case 'object':
                $this->fetchStyle = PDO::FETCH_OBJ;
                break;
            case 'both':
                $this->fetchStyle = PDO::FETCH_BOTH;
                break;
            default:
                $this->fetchStyle = PDO::FETCH_OBJ;
                break;
        }
    }

    /**
     * Fetch single row
     *
     * @return array
     */
    public function fetch()
    {
        return $this->statement->fetch($this->fetchStyle);
    }

    /**
     * Fetch all rows
     *
     * @return array
     */
    public function fetchAll()
    {
        return $this->statement->fetchAll($this->fetchStyle);
    }

    /**
     * Count all query rows
     *
     * @return int
     */
    public function count()
    {
        return $this->statement->rowCount();
    }
}
