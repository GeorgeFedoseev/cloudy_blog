<?php

class Post{

	private $id, $title, $body, $extra, $rating, $time_created, $time_edited;
	
	public function __construct($p = null){
		if($array){
			$this->id = $p['id'];
			$this->title = $p['title'];
			$this->body = $p['body'];
			$this->rating = $p['extra'];
			$this->time_created = $p['time_created'];
			$this->time_edited = $p['time_edited'];
		}
	}

	public function getId(){ return $this->id; }
	public function getTitle(){ return $this->title; }
	public function getBody(){ return $this->body; }
	public function getCreated(){ return $this->time_created; }
	public function getEdited(){return $this->time_edited;}
	public function getExtra(){return $this->extra;}
	public function getRating(){ return $this->rating;}
    
    public function setId($val){ $this->id = $val; return $this;}
	public function setTitle($val){ $this->title = $val; return $this;}
	public function setBody($val){ $this->body = $val; return $this;}
	public function setCreated($val){ $this->time_created = $val; return $this;}
	public function setEdited($val){ $this->time_edited = $val; return $this;}
	public function setExtra($val){ $this->extra = $val; return $this;}
	public function setRating($val){ $this->rating = $val; return $this;}	




}



?>