<?


class TagMapper{



	public function saveTags(Post $post, $tags){
		$tagsArray = explode(",", $tags);
		 foreach($tagsArray as $i=>$tag){
		 	if($tagName = Filter::alphaDigit(Filter::cleanString($tag)))
		 	  $tagsArray[$i] = $tagName;
		 	else
		 	  unset($tagsArray[$i]);
		 }

		Db::execQuery("DELETE FROM postntag WHERE post = '{$post->getId()}'");

		 foreach ($tagsArray as $tagName) {
		 	if(!($tag = Db::getObjectByQuery("SELECT * FROM tags WHERE tag = '$tagName'", "Tag")));
		 	  $tag = TagMapper::addTag($tagName);
		 	TagMapper::connect($post, $tag);
		 }
	}

	public static function getTagByName($tagName){
		$tagName = mysql_escape_string($tagName);
			return Db::getObjectByQuery("SELECT * FROM tags WHERE tag = '$tagName'", "Tag");
	}

	public function addTag($name){
		if($name){
			$tag = new Tag();
			 $tag->setTag($name);
			  $tag->setCreated(date('U'));
			 self::save($tag);
			return $tag;
		}		
	}

	public function save(Tag $tag){
		if($tag->getId()){
			Db::execQuery("UPDATE tags SET tag = '{$tag->getTag()}', time_created = '{$tag->getCreated()}'");
		}else{
			Db::execQuery("INSERT INTO tags (tag, time_created) VALUES('{$tag->getTag()}', '{$tag->getCreated()}')");
			 $tag->setId(mysql_insert_id());
		}
	}

	public function connect(Post $post, Tag $tag){
		if(!$connection = Db::getElementByQuery("SELECT FROM postntag WHERE post = '$post->getId()' AND tag = '$tag->getId()'"))
			Db::execQuery("INSERT INTO postntag (tag, post) VALUES('{$tag->getId()}', '{$post->getId()}')");
	}
}

?>