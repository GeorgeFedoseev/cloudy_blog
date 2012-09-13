<?


class ActionAddComment{
	private $commentText;
	private $postId;

	public function __construct(Request $request){
		Db::connectDatabase();
		
			if(!$this->postId = (int) $request->getParam(0))
				new Error("comments", "No post id was given to connect", __LINE__, __FILE__);
			$this->commentText = mysql_escape_string($request->getPost('comment'));			
	}

	public function run(){
		if($post = PostMapper::getPostById($this->postId)){
			if($this->commentText){
				$commentId = CommentMapper::addComment($post, $this->commentText);	
				 header("Location: /post/{$this->postId}/#comment".$commentId);
			}else{
				View::setThroughData(array("error" => "Введите текст комментария"));
				 header("Location: /post/{$this->postId}/#addcomment");
			}
			
		}else{
			new Error("comments", "post id is wrong", __LINE__, __FILE__);
		}
	  return true;
	}
}



?>