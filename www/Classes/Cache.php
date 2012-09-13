<?


class Cache{
	
	public static function set($id, $val){
		$val = mysql_escape_string($val);
		$id = mysql_escape_string($id);

		if(Db::getElementByQuery("SELECT * FROM cache WHERE id = '$id'")){
			Db::execQuery("UPDATE cache SET value = '$val' WHERE id = '$id'");
		}else{
			Db::execQuery("INSERT INTO cache (id, value) VALUES('$id', '$val')");
		}
	}

	public static function get($id){
		$res =  Db::getElementByQuery("SELECT * FROM cache WHERE id = '$id'");
		 return $res['value'];
	}

	public static function clearCache(){
		Db::execQuery("DELETE FROM cache");
	}

}


?>