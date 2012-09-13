<?


class Error{
    
    function Error($errorType, $message, $line, $file){
        trigger_error("<span style='background:#EE563C;padding: 5px;'><b>$errorType:</b> $message Line: <b>$line</b> in <b>$file</b></span>  ", E_USER_ERROR);
         die;
    }
}


?>