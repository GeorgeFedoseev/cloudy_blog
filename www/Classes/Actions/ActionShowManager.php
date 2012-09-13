<?


class ActionShowManager{

    private $view;
    private $show;

    public function __construct(Request $r){
        Db::connectDatabase();
        
        switch($show = $r->getVar('show')){
            case 'banned':
                $this->show = USER_STATUS_BANNED;
             break;
            case 'removed':
                $this->show = USER_STATUS_REMOVED;
             break;
            default:
                $this->show = USER_STATUS_ACTIVE;
        }

    }

    public function run(){
        if(($user = UserMapper::getUser()) && ($user->getRights() == USER_RIGHTS_ADMIN)){
            $view = new View("Manager", "Default");
             $view->title = "UserManager";
             $view->show = $this->show;

             $view->activeUsers = UserMapper::getActiveUsers();
             $view->bannedUsers = UserMapper::getBannedUsers();
             $view->removedUsers = UserMapper::getRemovedUsers();


              $view->render();
            return true;
        }

        return false;
        
    }
}

?>