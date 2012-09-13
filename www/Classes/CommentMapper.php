<?

class CommentMapper{


	public static function getCommentById($id){
		$id = (int) $id;
			return Db::getObjectByQuery("SELECT * FROM comments WHERE id = '$id'", "Comment");
	}

	public static function addComment(Post $post, $commentText){
		if(!$commentText)
			new error("comments", "no comment text was given", __LINE__, __FILE__);

		if($activeUser = UserMapper::getUser()){
			$comment = new Comment();
				$comment->setComment(Filter::cleanPostBody($commentText));
				  $comment->setCreated(time());
			self::saveComment($comment, $post, $activeUser);
		}else{
			new error("userManagment", "trying to add comment without authorization", __LINE__, __FILE__);
		}
	}

	public static function removeComment(Comment $comment){
		Db::execQuery("DELETE FROM comments WHERE id = '{$comment->getId()}'");
		 Db::execQuery("DELETE FROM authorncomment WHERE comment = '{$comment->getId()}'");
		  Db::execQuery("DELETE FROM commentnpost WHERE comment = '{$comment->getId()}'");
	}

	public static function rateComment(Comment $comment, $positive = true, $value){
		$user = UserMapper::getUser();
		Db::execQuery("INSERT INTO authornratedcomment (author, comment, value) VALUES('{$user->getId()}', '{$comment->getId()}', '$userFactor')");
		 Db::execQuery("UPDATE comments SET rating = rating + '$value' WHERE id = '{$comment->getId()}'"); 					  
			return array("update" => $value, "full" => $comment->getRating()+$value);
	}
		public static function alreadyRated(Comment $comment, User $user){
            if(Db::getElementByQuery("SELECT * FROM authornratedcomment WHERE author = '{$user->getId()}' AND comment = '{$comment->getId()}'")){
                return true;
            }
                return false;                
        }

	public static function saveComment(Comment $comment, Post $post, User $user){
		if($comment->getId()){
			Db::execQuery("UPDATE comments SET comment = '{$comment->getComment()}, time_created = '{$comment->getCreated()}'");
		}else{
			Db::execQuery("INSERT INTO comments (comment, time_created) VALUES('{$comment->getComment()}', '{$comment->getCreated()}')");
			 $comment->setId(mysql_insert_id());
			 	Db::execQuery("INSERT INTO commentnpost (comment, post) VALUES ('{$comment->getId()}', '{$post->getId()}')");
			 	Db::execQuery("INSERT INTO authorncomment (author, comment) VALUES ('{$user->getId()}', '{$comment->getId()}')");
		}
	}

	public static function getOwner(Comment $comment){
		return Db::getObjectByQuery("
										SELECT authors.* FROM authors LEFT JOIN authorncomment ON authorncomment.author = authors.id
																	  LEFT JOIN comments ON authorncomment.comment = comments.id
														 WHERE comments.id = '{$comment->getId()}'
									", "User");
	}

	public static function getPost(Comment $comment){
		return Db::getObjectByQuery("
					SELECT posts.* FROM posts LEFT JOIN commentnpost ON posts.id = commentnpost.post
											  LEFT JOIN comments ON commentnpost.comment = comments.id
									WHERE comments.id = '{$comment->getId()}'
			", "Post");
	}
}

?>