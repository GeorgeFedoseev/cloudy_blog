<?

class User{

	private $id, $email, $passwordHash, $rights, $rating, $autoLoginHash, $status, $time_registered;

	public function __construct(){}

	public function getId(){ return $this->id;}
	public function getEmail(){ return $this->email;}
	public function getRegistered(){ return $this->time_registered;}
	public function getRights(){ return (int)$this->rights;}
	public function getRating(){ return $this->rating;}
	public function getPasswordHash(){return $this->passwordHash;}
	public function getALHash(){return $this->autoLoginHash;}
	public function getStatus(){return (int)$this->status;}


	public function setId($val){ $this->id = $val;}
	public function setEmail($val){ $this->email = $val;}
	public function setRegistered($val){ $this->time_registered = $val;}
	public function setRights($val){ $this->rights = $val;}	
	public function setRating($val){ $this->rating = $val;}	
	public function setPasswordHash($val){$this->passwordHash = $val;}
	public function setALHash($val){ $this->autoLoginHash = $val;}	
	public function setStatus($val){ $this->status = $val;}	

	
}


?>