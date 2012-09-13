<?

class GoToThe{
    public static function url($url){
        header('Location: '.$url);
    }

    public static function back($params){
        if(($index = in_array(BACK_PARAM_NAME, $params)) !== false){
            if($backUrlParams = array_slice($params, ($index + 1))){
                self::url("/".implode("/", $backUrlParams)."/");
                 return;
            }
        }

       self::url("/");
    }
}

?>