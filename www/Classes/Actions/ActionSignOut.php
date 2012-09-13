<?

class ActionSignOut{

	public function __construct(){}

	public function run(){
		UserMapper::signOut();
		 header("Location: ".$_SERVER["HTTP_REFERER"]);
		return true;
	}
}

?>