<?php
    class Database {
    	public $dbconn;
    	public function __construct(){
    		$server= "localhost";
    		$user='root';
    		$pass='';
    		$dbname='evacinfo';
    		$this->dbconn = mysqli_connect($server,$user,$pass,$dbname);
    		if(mysqli_connect_errno()){
    			echo "Error: could not connect to database.";
    			exit;
    		}else{
    			// echo "connected to database succesfully.";
    		}
    	}

    	public function testing(){
    		echo "connected to database succesfully.";
    	}	
    }
// $test = new database();
?>