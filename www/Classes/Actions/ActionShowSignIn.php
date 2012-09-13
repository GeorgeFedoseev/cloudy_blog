<?


class ActionShowSignIn{

    private $view;

    public function __construct(Request $r){
        $this->view = new View("SignIn", "Default");
         $this->view->title = 'Авторизация - '.UI_NAME_FULL;
    }

    public function run(){                
        $this->view->render();
         return true;
    }
}

?>