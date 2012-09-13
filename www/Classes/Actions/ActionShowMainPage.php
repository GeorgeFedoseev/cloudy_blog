<?


class ActionShowMainPage{

	private $view;
	private $page;

    public function __construct(Request $r){
      Db::connectDatabase();
      
    	$this->view = new View("MainPage", "Default");    	      
        
          $this->page = $r->getVar('page')?$r->getVar('page'):1; 
    }

    public function run(){    	
        $this->view->title = "Все посты - ".( ($this->page)?'Стр. '.$this->page:"" ).' - '.UI_NAME_FULL;
         $this->view->page = $this->page;
         
          $this->view->postsCount = PostMapper::getAllPostsCount();
          if($this->view->posts = PostMapper::getPosts(($this->page-1)*POSTS_PER_PAGE, POSTS_PER_PAGE)){

             $this->view->render();      
               return true;
          }
        return false;
    }
}

?>