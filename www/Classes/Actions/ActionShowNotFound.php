<?

class ActionShowNotFound{

    private $view;

    public function __construct(){}

    public function run(){
        $this->view = new View("NotFound", "Default");
         $this->view->title = "404 - Страница не найдена";
          $this->view->render();
           return true;
    }
}

?>