<?php
require __DIR__ . "/inc/bootstrap.php";
 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
 
// if ((isset($uri[3]) && $uri[3] != 'categories') || !isset($uri[4])) {
//     header("HTTP/1.1 404 Not Found");
//     exit();
// }
// var_dump($uri);

if ((!isset($uri[3]))) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

switch ($uri[3]) {
    case 'categories':
        require PROJECT_ROOT_PATH . "/Controller/Api/CategoryController.php";
        $objFeedController = new CategoryController();
        $objFeedController->listAction();
        break;

    case 'posts':
        require PROJECT_ROOT_PATH . "/Controller/Api/PostController.php";
        $objFeedController = new PostController();        
        if(isset($uri[4])){
            $objFeedController->listId($uri[4]);
        }else{
            $objFeedController->listAction();
        }
        break;
    default:
        # code...
        break;
}
