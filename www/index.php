<?

require_once "/config.php";

	$controller = new Controller(new Request($_GET['req'], $_POST));
	 $controller->processRequest();

?>