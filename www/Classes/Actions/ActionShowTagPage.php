<?


class ActionShowTagPage{

	private $tag;
	private $view;

	public function __construct(Request $r){
		Db::connectDatabase();
		
		$this->view = new View("TagSearch", "Default");		 
		$this->tag = Filter::cleanString($r->getParam(0));
		 $this->view->title = 'Поиск по тегу '.$this->tag;
		 $this->view->tag = $this->tag;
	}

	public function run(){
		$this->view->posts = PostMapper::getPostsByTag(TagMapper::getTagByName($this->tag));		
		$this->view->render();
			return true;
	}
}

?>