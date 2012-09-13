<?


class Decorate{
	public static function time($unix){
		return date("j F Y H:i", $unix);
	}

	public static function oldKeeper(){
		 return "";
	}

    public static function getName(User $user, $link = true, $section = "posts"){
        $name = preg_replace("/@.*/i", "", $user->getEmail());
        $userPic = "/Classes/Utils/PastelHash.php?W=200&H=50&hash=".md5($user->getEmail())."&opt=".(($user->getStatus() !== USER_STATUS_ACTIVE)?"grayscale":"");
         return $link?"<a title='↑↓".((($rt=$user->getRating()) > 0)?("+".$rt):$rt)."' style=\"background: url('".$userPic."'); background-size: contain;\" class='userName' href='/user/{$user->getId()}/show/$section/'>$name</a>":$name;
    }

    public function newLines($str){
        return preg_replace("/\n/ui", "<br>", $str);
    }

    public function feelNumber($num){
       if($num < 0)
        return '<span class="num_negative">'.$num.'</span>';
       elseif($num > 0 )
        return '<span class="num_positive">+'.$num.'</span>';
       else
        return '<span class="num_neutral">0</span>';
    }

    public static function pager($itemsCount, $perPage, $currentPage, $path, $align = 'left'){
        $numberOfPages = $itemsCount/$perPage;
         $postsLeft = $itemsCount - $perPage*$currentPage + 1;          
           $rs = "";
           if($numberOfPages > 1){
                $rs .= '<div class = pager align = '.$align.'>';
                    if($currentPage > 1){
                         $rs .= '<span class = pagerBack>
                                <a href = "'.$path.'/page/'.($currentPage-1).'">назад</a>
                               </span>';
                    }   
                        $len = 10;
                    
                    if($currentPage + $len/2 < $numberOfPages)
                     $to = $currentPage+$len/2;
                    else
                     $to = $numberOfPages;
                    
                    if($currentPage - $len/2 > 0)
                     $i = $currentPage - $len/2;
                    else
                     $i = 1;  
                    
                    for(; $i < $to+1; $i++){
                        if($i == $currentPage)
                          $rs .= '<span class = pagerCurrent><a href = "'.$path.'/page/'.$i.'">'.$i.'</a></span>';
                        else
                         $rs .= '<a href = "'.$path.'/page/'.$i.'">'.$i.'</a>';
                    }

                    if($postsLeft>1){
                         $rs .= '<span class = pagerForward>
                            <a href = "'.$path.'/page/'.($currentPage+1).'">вперед</a>
                           </span>';
                    }
                $rs .= '</div>';
            }
        return $rs;
    }

    public static function showExtra($extra, $preview = false){
        if($extra = (string) $extra){
            if(stristr($extra, "vimeo.com")){
                preg_match("/^.*vimeo\.com\/([0-9].*?)$/ui", $extra, $matches);
                $id = (int)$matches[1];
                    if(!stristr($resp = @Cacheable::fileGetContents("http://vimeo.com/api/v2/video/$id.php"), "not found")){
                       $video = unserialize($resp);              
                         $thumbnail = $video[0]['thumbnail_medium'];

                          if($preview){
                             return "<img type='video' class='thumbnail' src='$thumbnail' width='".UI_EXTRATHUMBNAIL_SIZE."'><span></span>";
                          }else{
                            return <<<FULL
                                <iframe src="http://player.vimeo.com/video/$id" width="100%" height="250"  frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
FULL;

                          }

                    }
             
            }elseif(stristr($extra, "youtube.com")){
                if($id = self::getYoutubeVideoId($extra)){
                    $thumbnail = "http://img.youtube.com/vi/$id/default.jpg";
                     if($preview){
                            return "<img type='video' class='thumbnail' src='$thumbnail' width='".UI_EXTRATHUMBNAIL_SIZE."'><span></span>";
                          }else{
                            return <<<FULL
                                <iframe width="100%" height="250" src="http://www.youtube.com/embed/$id" frameborder="0" allowfullscreen></iframe>
FULL;

                              }

                    }
                }elseif(($imageData = @Cacheable::fileGetContents($extra))  ){
                  $thumbnail = $extra;
                  
                    if($preview){
                        return "<img type='image' class='thumbnail' src='data:image/gif;base64,".base64_encode($imageData)."' width='".UI_EXTRATHUMBNAIL_SIZE."'>";    
                    }else{
                        $size = UI_MAX_EXTRAIMAGE_SIZE;
                        return "<img src='data:image/gif;base64,".base64_encode($imageData)."' width='$size'>";
                    }
                }
            }
        }
        
        
            private function getYoutubeVideoId($url){
                $parsedUrl = parse_url($url);
                if ($parsedUrl === false)
                    return false;

                if (!empty($parsedUrl['query']))
                {
                    $query = array();
                    parse_str($parsedUrl['query'], $query);
                    if (!empty($query['v']))
                        return $query['v'];
                }

                if (in_array(strtolower($parsedUrl['host']), array('youtu.be', 'www.youtu.be')))
                    return trim($parsedUrl['path'], '/');

                return false;
            }




}

?>