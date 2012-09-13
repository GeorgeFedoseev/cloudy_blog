<?


class ActionRate{

	private $type;
	private $direction;
	private $id;

	public function __construct(Request $r){
		Db::connectDatabase();
		
		$this->type = ($r->getParam(0) == 'post')?'post':'comment';
		 $this->direction = ($r->getPost('direction') == 'true')?true:false;
		  $this->id = (int) $r->getPost('id');

	}

	public function run(){

		if($activeUser = UserMapper::getUser()){
			if($activeUser->getRating() > USER_ALLOW_RATE_RATING){
				if($this->type == 'post'){			
					if($post = PostMapper::getPostById($this->id)){					
						if(!UserMapper::ownership($post, $activeUser)){
							if(!PostMapper::alreadyRated($post, $activeUser)){
								$rateValue = USER_RATE_FACTOR*abs($activeUser->getRating())*($this->direction?1:(-1));
								print json_encode(PostMapper::ratePost($post, $this->direction, $rateValue));
								 UserMapper::updateRating(PostMapper::getOwner($post));
							}else{
								print json_encode(array("error" => "Вы уже проголосовали"));				
							}						
						}else{
							print json_encode(array("error" => "Нельзя оценивать свой пост"));		
						}				
					}else{
						print json_encode(array("error" => "Такого поста нет"));
					}
				}else{
					if($comment = CommentMapper::getCommentById($this->id)){				
						if(!UserMapper::ownershipForComment($comment, $activeUser)){
							if(!CommentMapper::alreadyRated($comment, $activeUser)){
								$rateValue = USER_RATE_FACTOR*abs($activeUser->getRating())*($this->direction?1:(-1));
								print json_encode(CommentMapper::rateComment($comment, $this->direction, $rateValue));
								 UserMapper::updateRating(CommentMapper::getOwner($comment));
							}else{
								print json_encode(array("error" => "Вы уже проголосовали"));				
							}	
						}else{
							print json_encode(array("error" => "Нельзя оценивать свой комментарий"));		
						}				
					}else{
						print json_encode(array("error" => "Такого комментария нет"));
					}
				}
			}else{
				print json_encode(array("error" => "Ваш рейтинг слишком мал"));	
			}
		}else{
			print json_encode(array("error" => "Авторизируйтесь"));
		}
		
	  return true;
	}

}



?>