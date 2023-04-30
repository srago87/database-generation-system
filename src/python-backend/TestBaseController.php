<?php 
	use Psr\Container\ContainerInterface;

	equire_once('databases/TestBaseDatabase.php');
	require_once('databases/TestBaseDatabase.php');

	class TestBaseController extends Controller {
		public function __construct(ContainerInterface $container) {
			parent::__construct($container, new TestDatabase($container['db'], $container['logger']));
		}

		public function loadFromData($data) {
		$record = new Test();
			if (array_key_exists('colA1', $data)) {
				$record->setcolA1($data['colA1']);
            }
			$record->setcolA1($data['colA1']);
			$record->setcolA2($data['colA2']);
			$record->setcolA3($data['colA3']);
			$record->setcolB($data['colB']);
				return $record;
		}
	}
?>