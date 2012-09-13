<?

class ActionCreatePost{

	private $title, $body, $tags;
	private $post;

	public function __construct(Request $r){	
		Db::connectDatabase();
		
		$this->title = Filter::cleanString($r->getPost('title'));
		 $this->body = Filter::cleanPostBody($r->getPost('body'));
		  $this->tags = Filter::cleanString($r->getPost('tags'));		   
		   $this->extra = Filter::cleanString($r->getPost('extra'));
	}

	public function run(){
		if(UserMapper::getUser()){
			if($this->title && $this->body){			
				$post = PostMapper::createPost($this->title, $this->body, $this->extra);
				  TagMapper::saveTags($post, $tags);
				  	header('Location: /post/'.$post->getId());
			}else{
				View::setThroughData(array('error' => 'Вы не указали название или текст поста'));
				 header("Location: /createpost/");
			}		
			   return true;
		}
			return false;
	}

}


?>