<?


class UserMapper{



	public static function getUserById($id){
		$id = (int) $id;
			return Db::getObjectByQuery("SELECT * FROM authors WHERE id = '$id'", "User");
	}


	public function signUp($email, $password, $rights){
		$user = new User();

		 $user->setEmail($email);
		 $user->setPasswordHash(md5($password.DB_SALT));
		 $user->setRights($rights);
		 $user->setRating(USER_START_RATING);
		 $user->setStatus(USER_STATUS_ACTIVE);
		 $user->setRegistered(date('U'));
		  UserMapper::save($user);
	}

	public function banUser(User $user, $days){
		$user->setStatus(USER_STATUS_BANNED);
		 UserMapper::save($user);
	}

	public function removeUser(User $user){
		$user->setStatus(USER_STATUS_REMOVED);
		 UserMapper::save($user);
	}

	public function activateUser(User $user){
		$user->setStatus(USER_STATUS_ACTIVE);
		 UserMapper::save($user);	 
	}
		public static function getActiveUsers(){
			return Db::getObjectsByQuery("SELECT * FROM authors WHERE status = '".USER_STATUS_ACTIVE."'", "User");
		}

		public static function getBannedUsers(){
			return Db::getObjectsByQuery("SELECT * FROM authors WHERE status = '".USER_STATUS_BANNED."'", "User");
		}

		public static function getRemovedUsers(){
			return Db::getObjectsByQuery("SELECT * FROM authors WHERE status = '".USER_STATUS_REMOVED."'", "User");
		}

    public function isFree($email){
        if(Db::getElementByQuery("SELECT id FROM authors WHERE email = '$email'"))
            return false;
        return true;
    }

	public function ownership(Post $post, User $user){		
		if($res = Db::getElementByQuery("SELECT * FROM authornpost WHERE author = '{$user->getId()}' AND post = '{$post->getId()}'"))		
			return true;
		return false;
	}	

	public function ownershipForComment(Comment $comment, User $user){		
		if($res = Db::getElementByQuery("SELECT * FROM authorncomment WHERE author = '{$user->getId()}' AND comment = '{$comment->getId()}'"))		
			return true;
		return false;
	}		

	public static function updateRating(User $user){
		$commentsRatingRes = Db::getElementByQuery("
									SELECT SUM(authornratedcomment.value) as sum FROM authornratedcomment 
											LEFT JOIN comments ON authornratedcomment.comment = comments.id
											LEFT JOIN authorncomment ON authorncomment.comment = comments.id
											LEFT JOIN authors ON authors.id = authorncomment.author
												WHERE authors.id = '{$user->getId()}'
							");
		 $commentsRating = $commentsRatingRes['sum']?$commentsRatingRes['sum']:0;

		$postsRatingRes = Db::getElementByQuery("
									SELECT SUM(authornratedpost.value) as sum FROM authornratedpost 
											LEFT JOIN posts ON authornratedpost.post = posts.id
											LEFT JOIN authornpost ON authornpost.post = posts.id
											LEFT JOIN authors ON authors.id = authornpost.author
												WHERE authors.id = '{$user->getId()}'
							");
		 $postsRating = $postsRatingRes['sum']?$postsRatingRes['sum']:0;

		 $user->setRating($commentsRating + $postsRating + USER_START_RATING);
		  self::save($user);
		   self::clearUserCache();


	}

	public function getUser(){
	  session_start();
		if($userObjectString = $_SESSION['user']){ # сессии юзаем всегда
			return unserialize($userObjectString);
		}elseif($ALHash = $_COOKIE['alh']){ # автологин если есть куки
			if($user = Db::getObjectByQuery("SELECT * FROM authors WHERE autoLoginHash = '".mysql_escape_string($ALHash)."'", "User")){
				return self::signIn($user->getEmail(), $user->getPasswordHash(), true);				 
			}
		}

		return false;		
	}

		public function clearUserCache(){
			 session_start();
			  $_SESSION['user'] = '';
		}

	public function getPosts(User $user, $page){
		$start = ($page-1)*POSTS_PER_PAGE;
    	 $count = POSTS_PER_PAGE;
		return Db::getObjectsByQuery("
										SELECT posts.* FROM posts LEFT JOIN authornpost ON authornpost.post = posts.id
																  LEFT JOIN authors ON authors.id = authornpost.author
													WHERE authors.id = '{$user->getId()}' ORDER BY time_created DESC LIMIT $start, $count
									 ", "Post");
	}

	public function getPostsCount(User $user){
		$res = Db::getElementByQuery("
										SELECT COUNT(posts.id) FROM posts LEFT JOIN authornpost ON authornpost.post = posts.id
																  LEFT JOIN authors ON authors.id = authornpost.author
													WHERE authors.id = '{$user->getId()}'
									 ");
			return $res[0];
	}

	public function getComments(User $user, $page){
		$start = ($page-1)*COMMENTS_PER_PAGE;
    	 $count = COMMENTS_PER_PAGE;
		return Db::getObjectsByQuery("
										SELECT comments.* FROM comments LEFT JOIN authorncomment ON authorncomment.comment = comments.id
																  LEFT JOIN authors ON authors.id = authorncomment.author
													WHERE authors.id = '{$user->getId()}' ORDER BY time_created DESC LIMIT $start, $count
									 ", "Comment");
	}

	public function getCommentsCount(User $user){		
		$res = Db::getElementByQuery("
										SELECT COUNT(comments.id) FROM comments LEFT JOIN authorncomment ON authorncomment.comment = comments.id
																  LEFT JOIN authors ON authors.id = authorncomment.author
													WHERE authors.id = '{$user->getId()}'
									 ");
		 return $res[0];
	}


	
	

	public function signIn($email, $password, $hash = false){
     $passwordHash = $hash?$password:md5($password.DB_SALT);
      if(($user = Db::getObjectByQuery("SELECT * FROM authors WHERE email = '".mysql_escape_string($email)."' AND passwordHash = '".$passwordHash."'", "User"))
      	 && ($user->getStatus() == USER_STATUS_ACTIVE)){
        session_start();
         $_SESSION['user'] = serialize($user);
          $autoLoginHash = self::generateHash();
          	$user->setALHash($autoLoginHash);
          	 setcookie("alh", $autoLoginHash, time()+60*60*60*24*30, "/");
           self::save($user);

        return $user;
      }

      return false;
    }

    	public function generateHash($length = 32){
		  $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
			  $numChars = strlen($chars);
			  $string = '';
			  for ($i = 0; $i < $length; $i++) {
			    $string .= substr($chars, rand(1, $numChars) - 1, 1);
			  }
		  return $string;
    	}

	public function signOut(){
      session_start();
       $_SESSION['user'] = '';
        setcookie('alh', null, time()-3600, "/");
    }
	

	public function changePassword(User $user, $oldPassword, $newPassword){
		if((md5($oldPassword.DB_SALT) == $user->getPasswordHash())){
			$user->passwordHash = md5($newPassword.DB_SALT);
		 	 UserMapper::save($user);	
		 	 	return true;
		}
			return false;		
	}

	public function save(User $user){	
		if($user->getId()){
			Db::execQuery("UPDATE authors SET  email = '{$user->getEmail()}', passwordHash = '{$user->getPasswordHash()}', rights = '{$user->getRights()}', rating = '{$user->getRating()}', autoLoginHash = '{$user->getALHash()}', status = '{$user->getStatus()}', time_registered = '{$user->getRegistered()}' WHERE id = '{$user->getId()}'");
		}else{
			Db::execQuery("INSERT INTO authors (email, passwordHash, rights, rating, status, time_registered) VALUES('{$user->getEmail()}', '{$user->getPasswordHash()}', '{$user->getRights()}', '{$user->getRating()}', '{$user->getStatus()}'. '{$user->getRegistered()}')");	
			 $user->setId(mysql_insert_id());			  
		}
	}
}


?>