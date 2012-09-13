<?


class ActionSignIn{

    private $email, $password;
    private $returnPage;

    public function __construct(Request $r){
        Db::connectDatabase();
        
        $this->email = Filter::cleanString($r->getPost('email'));
         $this->password = Filter::cleanString($r->getPost('password'));
    }

    public function run(){
        if($this->email && $this->password){          
            if(UserMapper::signIn($this->email, $this->password)){
                header("Location: /");              
            }else{
                View::setThroughData(array('error' => 'Неверный e-mail или пароль или Ваш аккаунт не активен'));
                 header("Location: /signin/");                 
            }
        }else{
          View::setThroughData(array('error' => 'Введите e-mail и пароль'));
            header("Location: /signin/");   
        }

         return true;
    }
}





?>