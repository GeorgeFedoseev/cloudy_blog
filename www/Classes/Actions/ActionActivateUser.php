<?

class ActionActivateUser{

    private $userId;

    public function __construct(Request $request){
        Db::connectDatabase();
        
        $this->userId = (int) $request->getParam(0);
    }

    public function run(){

        if($this->userId){
            if(($user = UserMapper::getUserById($this->userId))
                && ($user->getId() !== UserMapper::getUser()->getId()) && (UserMapper::getUser()->getRights() == USER_RIGHTS_ADMIN) ){
               
                UserMapper::activateUser($user);
                 header("Location: /manager/");
                 return true;
            }
        }

        return false;
        
    }
}



?>