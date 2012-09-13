<?

class Filter{

    public static function cleanString($str){
        return mysql_escape_string(
                    trim(
                        preg_replace("/\s{2,}/", "", 
                           self::cleanHtml(
                                preg_replace('/\"/ui', "", 
                                     preg_replace("/\'/ui", "", 
                                        $str
                ))))));
    }

    public static function cleanPostBody($str){
        return mysql_escape_string(
                    trim(
                        preg_replace("/\s{2,}/", "", 
                            self::cleanHtml(
                                preg_replace('/\"/ui', "", 
                                     preg_replace("/\'/ui", "", 
                                        $str
                )),
                            /* cleanHtml*/ "<a><b>"
                            ))));
    }

    public static function cleanHtml($input, $validTags = ''){ 
        $regex = '#\s*<(/?\w+)\s+(?:on\w+\s*=\s*(["\'\s])?.+?\(\1?.+?\1?\);?\1?|style=["\'].+?["\'])\s*>#is'; 
        return preg_replace($regex, '<${1}>',strip_tags($input, $validTags)); 
    } 
        
    public static function alphaDigit($str){
        return preg_replace("/[^a-zA-ZА-Яа-яёЁ0-9,\- ]/ui", "", $str);
    }
    
    public static function  checkEmail($str){
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    public static function checkUrl($str){
        return filter_var($str, FILTER_VALIDATE_URL);
    }

    public static function checkIp($str){
        return filter_var($str, FILTER_VALIDATE_IP);
    }

    public static function checkPasswordStrength($str){
        if(strlen($str) > 5 && preg_match("/[A-ZА-ЯёЁ]/ui", $str) && preg_match("/[0-9]/ui", $str))
            return true;
        return false;
    }

    public static function limitString($str, $max_length = 500) {      
        if (strlen($str) > $max_length){  
                $str = substr($str, 0, $max_length);  
                $pos = strrpos($str, " ");  
                if($pos === false) {  
                        return substr($str, 0, $max_length)."...";  
                }  
                    return substr($str, 0, $pos)."...";  
        }else{  
            return $str;  
        }  
         
    }
}


?>