<?

class ActionRemovePost{
	private $id;
	private $r;

	public function __construct(Request $r){
		Db::connectDatabase();
		
		$this->id = (int) $r->getParam(0);
		 $this->r = $r;
	}

	public function run(){
		if($post = PostMapper::getPostById($this->id)){			
			if(($user = UserMapper::getUser()) && ($user->getRights() == USER_RIGHTS_ADMIN || UserMapper::ownership($post, $user))){
				PostMapper::remove($post);
					GoToThe::back($this->r->getParam());
				 return true;
			}
		}
		
		return false;
	}
}


?>