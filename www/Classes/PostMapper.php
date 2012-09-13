<?

class PostMapper{

	public function createPost($title, $body, $extra = null){
		$post = new Post();
		 $post->setTitle($title);
		  $post->setBody($body);
		   $post->setExtra($extra);

		   $post->setCreated(date('U'));
		    $post->setEdited(date('U'));
		     self::save($post);
		return $post;
	}

	public function save(Post $post){
        $user = UserMapper::getUser();

		if($post->getId()){
			Db::execQuery("UPDATE posts SET title = '{$post->getTitle()}', body = '{$post->getBody()}', extra = '{$post->getExtra()}', time_created = '{$post->getCreated()}', time_edited = '{$post->getEdited()}' WHERE id = '{$post->getId()}'");
		}else{
			//die("INSERT INTO posts (title, body, time_created, time_edited) VALUES('{$this->getTitle()}', '{$this->getBody()}', '{$this->getCreated()}', '{$this->getEdited()}')");

			Db::execQuery("INSERT INTO posts (title, body, extra, time_created, time_edited) VALUES('{$post->getTitle()}', '{$post->getBody()}', '{$post->getExtra()}', '{$post->getCreated()}', '{$post->getEdited()}')");
			 $post->setId(mysql_insert_id());
			   # прикрепляем к пользователю
			  	 Db::execQuery("INSERT INTO authornpost (author, post) VALUES('{$user->getId()}', '{$post->getId()}')");			 			   
		}
	}

	public function remove(Post $post){
		Db::execQuery("DELETE FROM posts WHERE id = '{$post->getId()}'");
	}

    public function getPostById($id){      
            $id = (int) $id;
                return Db::getObjectByQuery("SELECT * FROM posts WHERE id = '$id'", 'Post');        
    }

    public static function ratePost(Post $post, $positive = true, $value){     
        $user = UserMapper::getUser();
                    
        Db::execQuery("INSERT INTO authornratedpost (author, post, value) VALUES('{$user->getId()}', '{$post->getId()}', '$userFactor')");
           Db::execQuery("UPDATE posts SET rating = rating + '$value' WHERE id = '{$post->getId()}'");                         
            return array("update" => $value, "full" => $post->getRating()+$value);                 
    }
        public static function alreadyRated(Post $post, User $user){
            if(Db::getElementByQuery("SELECT * FROM authornratedpost WHERE author = '{$user->getId()}' AND post = '{$post->getId()}'")){
                return true;
            }
                return false;                
        }

	 public function getOwner(Post $post){
        return Db::getObjectByQuery("
                                              SELECT authors.* FROM authornpost LEFT JOIN authors
                                                ON authornpost.author = authors.id
                                                  WHERE authornpost.post = '{$post->getId()}'
                                           ", "User");
    }    

    public static function getTags(Post $post){
    	return Db::getObjectsByQuery("
    									SELECT tags.* FROM tags LEFT JOIN postntag ON tags.id = postntag.tag
    														    LEFT JOIN posts ON posts.id = postntag.post
    														WHERE posts.id = '{$post->getId()}'
    						         ", "Tag");
    }
        public function getTagsAsString(Post $post){
            if($tags = self::getTags($post)){
                foreach ($tags as $tag) {
                    $tagNames[] = $tag->getTag();
                }
              return implode(', ', $tagNames);
            }
              return "";        
        }

    public static function getComments(Post $post){
    	return Db::getObjectsByQuery("
    									SELECT comments.* FROM comments LEFT JOIN commentnpost ON comments.id = commentnpost.comment
    														    LEFT JOIN posts ON posts.id = commentnpost.post
    														WHERE posts.id = '{$post->getId()}'
    						         ", "Comment");
    }

    public static function getCommentsCount(Post $post){
    	$res = Db::getElementByQuery("
    									SELECT COUNT(comments.id) FROM comments LEFT JOIN commentnpost ON comments.id = commentnpost.comment
    														    LEFT JOIN posts ON posts.id = commentnpost.post
    														WHERE posts.id = '{$post->getId()}'
    						         ");
    	 return $res[0];
    }

    public static function getAllPostsCount(){    
       $res = Db::getElementByQuery('SELECT COUNT(id) FROM posts'); 
        return $res[0];    
    }

    public static function getPosts($start = 0, $count = null){
        if($count == null) $count = self::getAllPostsCount();

        return Db::getObjectsByQuery("SELECT * FROM posts ORDER BY time_created DESC LIMIT $start, $count", "Post");
    }

    public static function getPostsByTag(Tag $tag){
       return  Db::getObjectsByQuery("
                                       SELECT posts.* FROM posts LEFT JOIN postntag ON posts.id = postntag.post
                                            LEFT JOIN tags ON tags.id = postntag.tag
                                            WHERE tags.tag = '{$tag->getTag()}' ORDER BY posts.time_created DESC
                                               ", "Post");
    }


}



?>