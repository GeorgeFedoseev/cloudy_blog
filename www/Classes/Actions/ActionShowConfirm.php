<?


class ActionShowConfirm{

    private $params;
    private $view;

    public function __construct(Request $r){
        $this->view = new View("Confirm", "Default");
        $this->params = $r->getParam();
    }

    public function run(){
        $backPath = parse_url($_SERVER['HTTP_REFERER']);

        $this->view->confirmHref = "/".implode("/", $this->params)."/".BACK_PARAM_NAME.$backPath['path']."/";
        $this->view->backHref = $_SERVER['HTTP_REFERER'];
         $this->view->render();
          return true;
    }
}


?>