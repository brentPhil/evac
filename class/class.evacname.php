<?php
    class Barangay extends Database{

    	public function save_brgy($name, $description){
            if($result=$this->dbconn->query("SELECT * FROM db_melvin WHERE brgy_name = '$name'")){
            	$row_cnt = $result->num_rows;
            	if($row_cnt==0){
            		$sql = mysqli_query($this->dbconn,"INSERT INTO db_melvin (brgy_name,brgy_description) VALUES('$name','$description')") or die (mysqli_connect_error());
            		return $sql;
            		$result->close();
            	}else{
            		return FALSE;
            	}
            }
    	}

    	public function get_all_brgy(){
            $sql = mysqli_query($this->dbconn,"SELECT * FROM db_melvin");
            $row = mysqli_fetch_all($sql, MYSQLI_ASSOC);
            return $row;
    	}
    }
?>