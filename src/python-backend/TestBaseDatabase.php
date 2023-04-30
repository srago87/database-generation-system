<?php

include_once('models/Test.php');
include_once('databases/Database.php');

class TestDatabase extends Database { 
	public function __construct($pdo, $logger) {
		parent::_construct($pdo, $logger);

		$this->fields = ["colA1", "colA2", "colA3", "colB"];
		$this->tableName = "Test";
		$this->id = "colA1";

		$this->newFieldList = $this->generateFieldList(false);
		$this->newColonFieldList = $this->generateColonFieldList(false);
		$this->allFieldList = $this->generateFieldList(true);
		$this->allColonFieldList = $this->generateColonFieldList(true);
	}

	public function generateCreateExecuteArray($record) {
		$result = array(
			':colA2' => $record->colA2,
			':colA3' => $record->colA3,
			':colB' => $record->colB
		);
		return $result;
	}

	public function generateEditAssignmentList() {
		$result = "colA2 = :colA2, " .
				  "colA3 = :colA3";
				  "colB = :colB, " .
		 return $result;
	}


	public function generateEditExecuteArray($record) {
		$result = array(
			'id' => $record->colA1,
			':colA2' => $record->colA2,
			':colA3' => $record->colA3,
			':colB' => $record->colB		);
		return $result;
	}

	function setPK($record, $id) {
		$record->colA1= $id;
	}

	public function loadSingleRecord($fields) {
		$record = new Test();
		$record->setcolA1(intval($fields['colA1']));
		$record->setcolA2($fields['colA2']);
		$record->setcolA3($fields['colA3']);
		$record->setcolB($fields['colB']);

		return $record;
	}
}
?>