<?

class Cacheable{
    public static function fileGetContents($url){
        if($contents = Cache::get(md5($url))){
            return $contents;
        }else{
            if($contents = file_get_contents($url)){
                Cache::set(md5($url), $contents);
                return $contents;
            }else{
                return null;
            }
        }
    }
}



?>