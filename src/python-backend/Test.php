<?php 
 
 include_once('models/Model.php');
 
class Test extends Model {
	public $colA1 = '';
	public $colA2 = '';
	public $colA3 = '';
	public $colB = '';


	public function getcolA1()
	{
		return $this->colA1;
	}

	public function setcolA1($colA1)
    {
		$this->colA1 = $colA1;
		return $this;
	}

	public function getcolA2()
	{
		return $this->colA2;
	}

	public function setcolA2($colA2)
    {
		$this->colA2 = $colA2;
		return $this;
	}

	public function getcolA3()
	{
		return $this->colA3;
	}

	public function setcolA3($colA3)
    {
		$this->colA3 = $colA3;
		return $this;
	}

	public function getcolB()
	{
		return $this->colB;
	}

	public function setcolB($colB)
    {
		$this->colB = $colB;
		return $this;
	}

}
?>