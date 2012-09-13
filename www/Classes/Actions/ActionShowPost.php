<?

class ActionShowPost{

    private $view;
    private $id;

    public function __construct(Request $r){
      Db::connectDatabase();
        
        $this->view = new View('PostPage', 'Default');
         $this->id = ($id = (int)$r->getParam(0))?$id:null;         
    }

    public function run(){
     
        if($post = PostMapper::getPostById($this->id)){           
            $this->view->title = $post->getTitle().' - '.UI_NAME_FULL;
            $this->view->post = $post;
             $this->view->postTags = PostMapper::getTags($post);
            $this->view->render();
              return true;
        }
          return false;
    }
}



?>