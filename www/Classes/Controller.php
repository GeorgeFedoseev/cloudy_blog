<?php


class Controller{

  private $request;

	public function controller($request){	      
		$this->request = $request;
	}
	

	public function processRequest(){
		if($action = $GLOBALS['actions'][$this->request->getAction()]){	    			
			$actionController = new $action($this->request);	

			 if(!$output = $actionController->run())			 	
			 	header("Location: /notfound/");

		}elseif(!$this->request->getAction()){
			header("Location: /all/");
		}else{
			new error("navigation", "no such action {$this->get->action}", __LINE__, __FILE__);
		}		

	}

}

?>