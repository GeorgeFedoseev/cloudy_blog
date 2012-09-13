<?

/*!*/ error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Europe/Moscow");
setlocale(LC_ALL, "ru_RU.UTF8");


function __autoload($className){
	if(strstr($className, 'Action'))
		require_once("/Classes/Actions/$className.php");
	else
	    require_once("/Classes/$className.php");
}

$const = "constant";

#userIntreface
define("UI_NAME_FULL", "Your Cloudy Blog");
define("UI_TEXTAREA_ROWS", 5);
define("UI_TEXTAREA_COLS", 32);
define("UI_EXTRATHUMBNAIL_SIZE", 120);
define("UI_MAX_EXTRAIMAGE_SIZE", '100%');

#userRights
define("USER_RIGHTS_ANONYMOUS", 0);
define("USER_RIGHTS_BASIC", 1);
define("USER_RIGHTS_ADMIN", 2);

#userStatus
define("USER_STATUS_ACTIVE", 0);
define("USER_STATUS_BANNED", 1);
define("USER_STATUS_REMOVED", 2);

#userRatings
define("USER_START_RATING", 0.50);
define("USER_RATE_FACTOR", 0.8);
define("USER_ALLOW_RATE_RATING", 0.20);

#pageNavigation
define("POSTS_PER_PAGE", 5);
define("COMMENTS_PER_PAGE", 2);
define("BACK_PARAM_NAME", 'backto');


define("STD_INCLUDE", <<<STDINCLUDE
		<script src="/js/jquery.js"></script>
		<script src="/js/ratings.js"></script>
		<script src="/js/effects.js"></script>
STDINCLUDE
	);

#url
define("MAX_URL_LENGTH", 128);
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

#database
define("DB_ADDRESS", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "george.blog");

define("DB_SALT", "CLOUDYSALT");


$actions = array(
						"all" => "ActionShowMainpage",
						 "post" => "ActionShowPost",
						 "createpost" => "ActionShowCreatePost",
						 "docreatepost" => "ActionCreatePost",
						 "edit" => "ActionShowEditPost",
						 "doeditpost" => "ActionEditPost",
						 "remove" => "ActionRemovePost", 
						 "tag" => "ActionShowTagPage",
						 "signin" => "ActionShowSignIn",
						 "dosignin" => "ActionSignIn",
						 "signup" => "ActionShowSignUp",
						 "dosignup" => "ActionSignUp",
						 "dosignout" => "ActionSignOut",
						 "user" => "ActionShowUserPage",
						 "addcomment" => "ActionAddComment",
						 "removecomment" => "ActionRemoveComment",
						 "rate" => "ActionRate",
						 "manager" => "ActionShowManager",
						 "removeuser" => "ActionRemoveUser",
						 "activateuser" => "ActionActivateUser",
						 "confirm" => "ActionShowConfirm",
						 "notfound" => "ActionShowNotFound"
					 );





?>