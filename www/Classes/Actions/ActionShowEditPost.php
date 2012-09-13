<?


class ActionShowEditPost{
	private $view;
	private $id;

	function __construct(Request $r){	
		Db::connectDatabase();
		
		$this->id = (int) $r->getParam(0);			 
 		 $this->view = new View("EditPost", "Default");
 		  $this->view->title = 'Редактирование поста';
 		   $this->view->action = '/doeditpost/'.$this->id;
 		    $this->view->buttonLabel = "Сохранить";
	}

	public function run(){
		if(!($post = PostMapper::getPostById($this->id))) 
		 	return false;
		$this->view->post = $post;
		$this->view->postTags = PostMapper::getTagsAsString($post);
		
		 $this->view->render();
		return true;
	}


}








?>