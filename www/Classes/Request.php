<?


class Request{

	private $action, $params, $post;

	public function __construct($path, $post){
		$this->buildRequest($path, $post);	
	}

	public function getAction(){
		return $this->action;
	}

	public function getParam($index = null){
		if($index !== null)
			return ($rv = $this->params[$index])?$rv:false;
		return $this->params;
	}
	
	public function getPost($index = null){
		if($index !== null)
			return ($rv = $this->post[$index])?$rv:false;
		return $this->post;
	}

	public function getVar($name){	
		if(($key = array_search($name, $this->params)) !== false){
			return ($val = $this->params[$key+1])?$val:false;
		}
	  		return false;	
	}

	public function getBack(){
		if($this->params[BACK_PARAM_NAME]){

		}
	}

	private function buildRequest($path, $post){
	    $requestArray = array();	    

	    if(strlen($path) > MAX_URL_LENGTH){
	    	new error('security', 'Too long url (max:'.MAX_URL_LENGTH.')', __LINE__, __FILE__);
	    }

	    # отчистка get частей
		 if($preArray = explode("/", trim($path))){
		 	foreach ($preArray as $key => $value) {
		 		if(trim($value))
		 		 $requestArray[$key] = trim($value);
		 	    else
		 	     unset($requestArray[$key]);
		 	}
		 }

		 $requestArray = array_values($requestArray);

		 $this->action = $requestArray[0];
		  unset($requestArray[0]);

		$this->params = array_values($requestArray);
		$this->post = $post;	
	}
}


?>