<?

class ActionRemoveComment{
	private $commentId;
	private $r;

	public function __construct(Request $r){
		Db::connectDatabase();
		
		if(!$this->commentId = (int) $r->getParam(0))
			new Error("comments", "comment id to delete was not given", __LINE__, __FILE__);
		$this->r = $r;
	}

	public function run(){
		if($comment = CommentMapper::getCommentById($this->commentId)){
			if(($activeUser = UserMapper::getUser()) && ($activeUser->getRights() == USER_RIGHTS_ADMIN || UserMapper::ownershipForComment($comment, $activeUser))){
				CommentMapper::removeComment($comment);
			 	 GoToThe::back($this->r->getParam());
			 	  return true;
			}
		}

	  return false;
	}
}

?>