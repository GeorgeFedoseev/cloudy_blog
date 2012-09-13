<?

class ActionShowUserPage{
	private $id, $user, $page, $show;
	private $view;

	public function __construct(Request $r){
		Db::connectDatabase();		

		$this->view = new View("UserPage", "Default");
		$this->id = (int) $r->getParam(0);	


		  if($page = $r->getVar('page'))
		  	$this->page = $page;
		  else
		  	$this->page = 1;
		  
		  if($show = $r->getVar('show'))
		  	$this->show = $show;
		  else
		  	$this->show = 'posts';

	}

	public function run(){
		if($this->user = Db::getObjectByQuery("SELECT * FROM authors WHERE id = '{$this->id}'", "User")){
			$this->view->title = "Творчество ".Decorate::getName($this->user, false)." - ".UI_NAME_FULL;
			$this->view->user = $this->user;
			$this->view->page = $this->page;


			 if(($this->view->show = $this->show) == 'posts'){
			 	$this->view->userPosts = UserMapper::getPosts($this->user, $this->page);
			 	 $this->view->userPostsCount = UserMapper::getPostsCount($this->user);
			 }elseif($this->show == 'comments'){
				 $this->view->userComments = UserMapper::getComments($this->user, $this->page);
				 	 $this->view->userCommentsCount = UserMapper::getCommentsCount($this->user);
			 }

			$this->view->render();
				 return true;
		}
	  return false;	
	}
}

?>