<?

class Tag{
	private $id, $tag, $time_created;

	public function __construct(){}

	public function getId(){return $this->id;}
	public function getTag(){return $this->tag;}
	public function getCreated(){return $this->time_created;}

	public function setId($val){$this->id = $val;}
	public function setTag($val){$this->tag = $val;}
	public function setCreated($val){$this->time_created = $val;}
}


?>