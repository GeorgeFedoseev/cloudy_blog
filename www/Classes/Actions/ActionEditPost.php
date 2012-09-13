<?

class ActionEditPost{
	private $id, $title, $body, $tags;
	private $post;

	public function __construct(Request $r){	
		Db::connectDatabase();
		
		$this->id = (int) $r->getParam(0);

		 if(!($this->post = PostMapper::getPostById($this->id))) 
		 	new Error("db", "no such post {$this->id}", __LINE__, __FILE__);

		 if(!(UserMapper::ownership($this->post, ($activeUser = UserMapper::getUser())) || $activeUser->getRights() == USER_RIGHTS_ADMIN)) 
		 	new Error("users", "you are not allowed to edit".print_r($activeUser, 1), __LINE__, __FILE__);

		$this->title = Filter::cleanString($r->getPost('title'));
		 $this->body = Filter::cleanPostBody($r->getPost('body'));
		  $this->tags = Filter::cleanString($r->getPost('tags'));		
		     $this->extra = Filter::cleanString($r->getPost('extra'));
	}

	public function run(){
		if($this->title && $this->body){
			$this->post->setTitle($this->title);		
			 $this->post->setBody($this->body);
			  $this->post->setExtra($this->extra);
			   $this->post->setEdited(date('U'));
				TagMapper::saveTags($this->post, $this->tags);
			    PostMapper::save($this->post);
			  	 header('Location: /post/'.$this->post->getId());	
		}else{
			View::setThroughData(array('error' => 'Вы не указали название или текст'));
			 header('Location: /edit/'.$this->id);
		}
		
		return true;
	}
}

?>