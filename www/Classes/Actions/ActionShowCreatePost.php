<?


class ActionShowCreatePost{
    private $view;

    public function __construct(Request $r){
      Db::connectDatabase();
      
        $this->view = new View("CreatePost", "Default");          
         $this->view->title = "Создание поста";
          $this->view->action = "/docreatepost";
           $this->view->buttonLabel = "Создать";
    }

    public function run(){
      if(!$user = UserMapper::getUser()) return false;
        $this->view->render();
        return true;
    }
}


?>