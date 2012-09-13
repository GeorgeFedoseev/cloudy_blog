<?

class Comment{
	private $id, $comment, $rating, $time_created;

	public function __construct(){}

	public function getId(){return $this->id;}
	public function getComment(){return $this->comment;}
	public function getRating(){return $this->rating;}
	public function getCreated(){return $this->time_created;}


	public function setId($val){$this->id = $val;}
	public function setComment($val){$this->comment = $val;}
	public function setRating($val){$this->rating = $val;}
	public function setCreated($val){$this->time_created = $val;}
}

?>