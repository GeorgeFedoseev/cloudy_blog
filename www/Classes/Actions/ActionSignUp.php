<?

class ActionSignUp{

    private $email, $password;

    public function __construct(Request $r){
        Db::connectDatabase();
        
        $this->email = Filter::cleanString($r->getPost('email'));
         $this->password = Filter::cleanString($r->getPost('password'));
    }

    public function run(){
        if($this->email && $this->password){
            if(Filter::checkEmail($this->email)){
                if(Filter::checkPasswordStrength($this->password)){
                    if(UserMapper::isFree($this->email)){
                        UserMapper::signUp($this->email, $this->password, USER_RIGHTS_BASIC);
                         UserMapper::signIn($this->email, $this->password);
                         //View::setThroughMessage("Проверьте почту");
                          header("Location: /all/");
                    }else{
                        View::setThroughData(array('error' => 'Пользователь с таким e-mail-ом уже зарегистрирован'));
                         header("Location: /signup/");    
                    }
                }else{
                    View::setThroughData(array('error' => 'Пароль должен содержать буквы и цифры и быть не короче 5 символов'));
                     header("Location: /signup/");
                }
            }else{
                    View::setThroughData(array('error' => 'Вы ввели неверный e-mail'));
                 header("Location: /signup/");
            }
        }else{
            View::setThroughData(array('error' => 'Введите e-mail и пароль'));
             header("Location: /signup/");
        }
      return true;
    }
}


?>