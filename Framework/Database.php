<?php

namespace  Framework;

use PDO;
use PDOException;
use PDOStatement;

class Database{
    public $conn;

    /**
     * Constructor for Database Class
     *
     * @param array $config
     */

    public function __construct($config){
        $dns = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};";


        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try{
            $this->conn = new PDO($dns, $config['username'], $config['password'], $options);
        }catch(PDOException $e){
            throw new PDOException("Data connection failed :{$e->getMessage()}");
        }
    }


    /**
     * Query the Database
     *
     * @Param string $query
     *
     * @return PDOStatement
     * @throws PDOException
     *
     */

    public function query($query, $params =[]): PDOStatement
    {
        try{
            $stmt = $this->conn->prepare($query);

            //Bind named params
            foreach ($params as $param => $value){

                //:id will become id
                $stmt->bindValue(':' . $param, $value);
            }

            $stmt->execute();
            return $stmt;
        }catch(PDOException $e){
            throw new PDOException("Query Failed to Execute :{$e->getMessage()}");
        }
    }

}