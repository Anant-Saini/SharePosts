<?php
    /* PDO Database Class
     * Connect to database
     * Create prepared statements
     * Bind Values
     * Return rows and resuls
     */
    class Database {
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $dbname = DB_NAME;

        private $dbh; //database handler
        private $stmt; //statement
        private $error; //For Errors

        public function __construct() {
            //Set DSN String
            $dsn = 'mysql:host='. $this->host .';dbname='. $this->dbname;
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            //Create PDO instance
            try {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            } catch(PDOException $e) {
                $this->error = $e->getMessage();
                echo 'Error: '.$this->error;
            }
        }
        //prepare an SQL Query
        public function query($sql) {
            $this->stmt = $this->dbh->prepare($sql);
        }
        //bind values to the prepared statement
        public function bind($param, $value, $type = null) {
            //Set Type If no type is provided
            if(is_null($type)) {
                switch(true) {
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
        //Execute the binded statement
        public function execute() {
            return $this->stmt->execute();
        }
        //Return the resultset as Array of Objects
        public function resultSet() {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }
        //Return only a single row of ResultSet as Object
        public function single() {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
        //Return the row count in resultSet
        public function rowCount() {
            return $this->stmt->rowCount();
        }
    }