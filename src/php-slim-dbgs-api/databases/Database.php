<?php

class Database {
	public function __construct($pdo, $logger) {
		$this->tableName = "";
		$this->newFields = "";
		$this->newColonFields = "";
        $this->id = "";
        $this->fields = null;
        $this->pdo = $pdo;
        $this->logger = $logger;


		$this->newFieldList = "";
		$this->newColonFieldList = "";
		$this->allFieldList = "";
		$this->allColonFieldList = "";

	}

    function setPK($record, $id) {

    }

    function generateFieldList($includePK) {
        $sep = "";
        $result = "";
        if ($includePK) {
            $result = $result . "`{$this->id}`"; 
            $sep = ",";
        }

        for ($i = 0; $i < count($this->fields); $i++) {
            $result = $result . "{$sep} `{$this->fields[$i]}`"; 
            $sep = ", ";
        }

        return $result;
    }

    function generateColonFieldList($includePK) {
        $result = "";
        $sep = "";

        if ($includePK) {
            $result = $result . ":`{$this->id}`"; 
            $sep = ",";
        }

        for ($i = 0; $i < count($this->fields); $i++) {
            $result = $result . "{$sep} :{$this->fields[$i]}"; 
            $sep = ", ";
        }

        return $result;
    }

	public function generateCreateExecuteArray($record) {
		$result = array(
			':field1' => "1",
			':field2' => "2",
			':field3' => "3"
		);
		return $result;
	}
    
	public function generateEditExecuteArray($record) {
		$result = array(
			':field1' => "1",
			':field2' => "2",
			':id' => "3"
		);
		return $result;
	}

    function generateEditAssignmentList() {
        $result = "";
        return $result;
    }


	public function create($record)  {
		try {
			$sql = "INSERT INTO {$this->tableName} " .
								"({$this->newFieldList}) VALUES " . 
								"({$this->newColonFieldList})";
			$query = $this->pdo->prepare($sql);

			$r = $this->generateCreateExecuteArray($record);
			$this->logger->debug("database create: " . $sql);
			$this->logger->debug("database create2: " . json_encode($r));
			$query->execute($r);

			$recordID = $this->pdo->lastInsertId();
			$this->setPK($record, $recordID);

			return $record;
		}  catch(Exception $e) {
			print( 'Caught exception: '.  $e->getMessage(). "\n");
			$this->logger->debug($e->getMessage());

			return null;
		}
	}

	public function read($recordID) {
        $sql = "SELECT {$this->allFieldList} FROM {$this->tableName} " .
                            " WHERE {$this->id} = :id";
		
		$query = $this->pdo->prepare($sql);
		$query->execute(array(
			':id' => $recordID			
        ));
        
		$record = $query->fetch();
		if($record != false) {
            $record = $this->loadSingleRecord($record);
            return $record;
		} else {
			return null;
		}
    }

    public function loadSingleRecord($fields) {
        return null;
    }
    
	public function readList() {
		$sql = "SELECT {$this->allFieldList} FROM `{$this->tableName}`";
		
		$query = $this->pdo->prepare($sql);

		$query->execute();
        
        $records = $query->fetchAll();
        
		$recordList = array();

		foreach ($records as $record) {
            $item = $this->loadSingleRecord($record);
			array_push($recordList, $item);
		}
		return $recordList;
	}

	public function update($record) {
        $editFieldList = $this->generateEditAssignmentList();
	    $sql = "UPDATE `{$this->tableName}` SET {$editFieldList} WHERE {$this->id} = :id";

        $query = $this->pdo->prepare($sql);


		$this->logger->debug("update: " . $sql);

		$r = $this->generateEditExecuteArray($record);
		//print_r($r);
		$this->logger->debug("update2: ". implode(', ',$r));

        $query->execute($r);
        
        return $record;
	}

	public function delete($id) {
	    $sql = "DELETE FROM `{$this->tableName}` WHERE {$this->id} = :id";
        $query = $this->pdo->prepare($sql);
        $query->execute(array(
			':id' => $id			
        ));
        return true;
	}
}
?>