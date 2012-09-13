<?

class ActionRemoveUser{

    private $userId;

    public function __construct(Request $r){
        Db::connectDatabase();
        
        $this->userId = (int) $r->getParam(0);
    }

    public function run(){
        if($this->userId){
            if(($user = UserMapper::getUserById($this->userId))
                && ($user->getId() !== UserMapper::getUser()->getId()) && (UserMapper::getUser()->getRights() == USER_RIGHTS_ADMIN) ){
                UserMapper::removeUser($user);
                 header("Location: /manager/");
                 return true;
            }
        }

        return false;
        
    }
}



?>