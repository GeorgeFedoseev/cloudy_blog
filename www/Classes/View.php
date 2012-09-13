<?php

class View{

	/*private $template;

	public function setTemplate($vat){$this->template = $val;}
	public function getTemplate($vat){return $this->template;}*/

	private $template;

	public function __construct($template, $base = ''){		
		if($base) $this->baseTpl = $base;
		if(!$this->innerTpl = $template) new error("coding", "Не было передано название шаблона", __LINE__, __FILE__);
		 $this->activeUser = UserMapper::getUser();		  
	}

	public function render($return = false){
		# setting up base template
		  if(file_exists($baseFilename = ROOT.'/Templates/Base/'.$this->baseTpl.'.phtml')){
		  	  if(file_exists($innerFilename = ROOT.'/Templates/'.$this->innerTpl.'.phtml')){
		  	  	 $this->innerTpl = $this->getIncludeOutput($innerFilename);
		  	  	  if($return){
		  	  	  	return $this->getIncludeOutput($baseFilename);
		  	  	  }else{
		  	  	  	include($baseFilename);
		  	  	  }
		  	  }else{
		  	 	 new error('files', 'No such file: '.$innerFilename, __LINE__, __FILE__); 	
		  	  }
		  }else{
		  	 new error('files', 'No such file: '.$baseFilename, __LINE__, __FILE__);
		  }
	  
	}

	public function fastRender($template, $base, $params, $return = false){
		$view = new View($template, $base);
		if($params){
		 	foreach($params as $name=>$value){
		 		$view->{$name} = $value;
		 	}
		 }
		 	return $view->render($return);
	}

	public function commonRender($template, $params = null){
		if(file_exists($templateFilename = ROOT.'/Templates/Common/'.$template.'.phtml')){
			$view = new View($template);
			 if($params){
			 	foreach ($params as $name=>$value) {
			 		$view->{$name} = $value;
			 	}
			 }
		  return $view->getIncludeOutput($templateFilename);
		}else{
			new error("commonRender", "Template not found", __LINE__, __FILE__);
		}
	}

	private function getIncludeOutput($filename){
		if (is_file($filename)) {
	        ob_start();
	          include $filename;
	        return ob_get_clean();
	    }

	    return false;
	}

	public static function setThroughData($data){
		session_start();
		 $_SESSION['throughData'] = serialize($data);		 
	}

	public static function getThroughData($param){
		session_start();
		   if($data = $_SESSION['throughData'])
		    $throughData = unserialize($data);		
		   $_SESSION['throughData'] = '';

		return $throughData[$param];
	}
}


/*
class View{

	private $combiner, $user;

	public function View(){
		$this->combiner = new Combiner();
		$this->user = new User();
		 $this->user->init();
	}

	private function head($title, $includes = ''){
		global $const;
		$includes .= STD_INCLUDE;		
		return <<<HEAD
			<!DOCTYPE html>
			<html>
				<head>
					<title>$title - {$const("UI_NAME_FULL")}</title>
						$includes
				</head>
				 <body>

				  <div id="header" class="cloud">				    
				    {$this->userBar()}
				    {$this->mainMenu()}				  	
				  </div>



				  <div id="container">

HEAD;
	}
		private function mainMenu(){
			if($this->user->getRights() >= USER_RIGHTS_BASIC){
				return <<<MENU_FULL
					<div id="mainMenu">
					  	<a id="create" href="/new">/создать</a>
					  	<a id="all" href="/allposts">/все посты</a>
				  	</div>
MENU_FULL;
			}else{
				return <<<MENU
					<div id="mainMenu">					  
					  	<a id="all" href="/allposts">/cloudycloud</a>
				  	</div>
MENU;
			}
		}

		private function userBar(){
			if(!$this->user->getRights()){
				return '<div id="userBar">
					  		<a href="/signin" id="sign">Войти</a>
					  	</div>';
			}else{
				return  '<div id="userBar">
					  		<a href="/signout" id="sign">Выйти</a>
					  	</div>';
			}
		}

	public function showPosts($page = 1){	
	  $rs = "";
	 	$rs .= $this->head("Все посты");
		   $rs .= "<div id='messages'>";		    
          
			if($posts = $this->combiner->getPosts( ($page-1)*POSTS_PER_PAGE, POSTS_PER_PAGE )  ) {
				$rs .= "<h2>Все посты</h2>";
			
			   $count = 0;
				 foreach ($posts as $post) {				 	
				 	if($count < $page*POSTS_PER_PAGE && $count >= ($page-1)*POSTS_PER_PAGE)
				 	 $rs .= $this->showMiniPost($post);
				 	
				 	 $count++;
				 }
			    $rs .= $this->pager(count($posts)/POSTS_PER_PAGE, count($posts) - POSTS_PER_PAGE*$page + 1, "/allposts", $page, "center");
			}else{
				$rs .= "<h2>Пока ничего нет</h2>";
			}

			$rs .= "</div>";
		$rs .= $this->feet();

		return $rs;
	}

		private function showMiniPost(Post $post){
			
				return <<<MINIPOST
				<div class="miniPost">
					<a href="/post/{$post->getId()}" class="title">{$post->getTitle()}</a>					
					<div class="body">{$post->getBody()}</div>
					<div class="info">
						<div class="created">{$this->getCreatorSign($post)} {$post->getCreated(false)}</div>
						<!-- <div class="edited">{$post->getEdited(false)}</div> -->
					</div>
					{$this->postOptions($post)}

				</div>
MINIPOST;
		}		
		
		public function showPost(Post $post){
			return <<<POST
				{$this->head($post->getTitle())}
				<div id="post">
					<h2 id="title">{$post->getTitle()}</h2>
					<div id="body">{$post->getBody()}</div>
					<div class="info">
						<div class="created">{$this->getCreatorSign($post)} {$post->getCreated(false)}</div>
						<!-- <div class="edited">{$post->getEdited(false)}</div> -->
					</div>		
					{$this->postOptions($post)}
				</div>
				{$this->feet()}
POST;
		}
			private function postOptions(Post $post){
				if($this->user->getRights() >= USER_RIGHTS_BASIC && $this->user->owns($post)){

					return <<<POSTOPTIONS_FULL
						<div class="postOptions">
							<a class="edit" href="/edit/{$post->getId()}">редактировать</a>
							<a class="remove" href="/remove/{$post->getId()}">удалить</a>
						</div>	
POSTOPTIONS_FULL;
				}
			}

			private function getCreatorSign(Post $post){
				$owner = $post->getOwner();
				 if($owner->getId() == $this->user->getId())
				     return "Написали Вы";
				 else
				     return "Написал ".preg_replace("/@.* /i", "", $owner->getEmail());
			}

		public function newPost(){
			if(!$_COOKIE['timeout'] || 1){
				return <<<ADDPOSTOK
					{$this->head("Новый пост")}
					<div id="addPost">
						<h2>Новый пост</h2>
						  <form method="post" action="/addpost">
						  	<input autocomplete="off" type="text" name="title" placeholder="Тема"><br/>
							<textarea rows="{$GLOBALS['const']("UI_TEXTAREA_ROWS")}" cols="{$GLOBALS['const']("UI_TEXTAREA_COLS")}" name="body" placeholder="Сообщение"></textarea><br/>
						    <input type="submit" value="Создать" />
						  </form>
					</div>
					{$this->feet()}
ADDPOSTOK;
			}else{

				return "{$this->head("Новый пост")}<h2>Только через 5 минут</h2>{$this->feet()}";
			}
			
		}

		public function editPost(Post $post){			
			//$this->checkRights();
				return <<<EDITMSG
					{$this->head("Редактировать пост")}
					<div id="editPost">
						<h2>Редактирование поста</h2>
						  <form method="post" action="/editpost/{$post->getId()}/">
						  	<input autocomplete="off" type="text" name="title" value="{$post->getTitle()}" placeholder="Тема"><br/>
							<textarea rows="{$GLOBALS['const']("UI_TEXTAREA_ROWS")}" cols="{$GLOBALS['const']("UI_TEXTAREA_COLS")}" name="body" placeholder="Сообщение">{$post->getBody()}</textarea><br/>
						    <input type="submit" value="Сохранить" />
						  </form>
					</div>
					{$this->feet()}
EDITMSG;

			
		}

		public function signIn($error = ''){
			return <<<SIGNIN
				{$this->head("Вход")}
				<div id="signIn">
					<h2>Вход</h2>
					 <a href="/signup" class = "helper">Еще не зарегистрированы?</a>
					 <form method="post" action="/dosignin">
					 	<input placeholder="E-mail" type="email" name="email" />					 	
					 	<input placeholder="Password" type="password" name="password" />
					 	<input type="submit" value="Войти" /> <div id="error">$error</div>
					 </form>
				</div>
				{$this->feet()}
SIGNIN;
		}

		public function signUp($error = ''){
			return <<<SIGNUP
				{$this->head("Регистрация")}
				<div id="signIn">
					<h2>Присоединяйтесь!</h2>
					<a href="/signin" class = "helper">Уже с нами?</a>
					 <form method="post" action="/dosignup">
					 	<input placeholder="E-mail" type="email" name="email" />					 	
					 	<input placeholder="Password" type="password" name="password" />
					 	<input type="submit" value="Тыц" /> <div id="error">$error</div>
					 </form>
				</div>
				{$this->feet()}
SIGNUP;
		}


		private function pager($numberOfPages, $left, $path, $current, $align = 'left'){
		   $rs = "";
		       if($numberOfPages > 1){
		            $rs .= '<div class = pager align = '.$align.'>';
		              	if($current > 1){
			                 $rs .= '<span class = pagerBack>
			                        <a href = "'.$path.'/page/'.$i.'">назад</a>
			                       </span>';
		                }   
		                	$len = 10;
		                
		                if($current + $len/2 < $numberOfPages)
		                 $to = $current+$len/2;
		                else
		                 $to = $numberOfPages;
		                
		                if($current - $len/2 > 0)
		                 $i = $current - $len/2;
		                else
		                 $i = 1;  
		               	
		               	for(; $i < $to+1; $i++){
		                    if($i == $current)
		                      $rs .= '<span class = pagerCurrent><a href = "'.$path.'/page/'.$i.'">'.$i.'</a></span>';
		                    else
		                     $rs .= '<a href = "'.$path.'/page/'.$i.'">'.$i.'</a>';
		                }

		              	if($left>1){
		                     $rs .= '<span class = pagerForward>
		                        <a href = "'.$path.'/page/'.($current+1).'">вперед</a>
		                       </span>';
		                }
		            $rs .= '</div>';
		        }
		    return $rs;
	    }

	
	private function feet(){
		return <<<FEET
		 			</div>
				</body>
			</html>
FEET;
	}
}
*/

?>