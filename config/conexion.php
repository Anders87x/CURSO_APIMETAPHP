<?php
    class Conectar{
        protected $dbh;

        protected function conexion(){
            try{
                $conectar = $this->dbh = new PDO("mysql:local=localhost;dbname=u831978754_webhook","u831978754_webhook","AnderCode100K");
                return $conectar;
            }catch(Exception $e){
                print "Error BD:" . $e->getMessage() . "<br>";
                die();
            }
        }

        public function set_names(){
            return $this->dbh->query("SET NAMES 'utf8'");
        }
    }
?>