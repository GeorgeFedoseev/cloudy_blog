<?

class ActionShowSignUp{

    private $view;

    public function __construct(Request $r){
        $this->view = new View("SignUp", "Default");
         $this->view->title = 'Регистрация - '.UI_NAME_FULL;
    }

    public function run(){                
        $this->view->render();
         return true;
    }
}


?>