<?php
class PostController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];        
 
        if (strtoupper($requestMethod) == 'GET') {
            if(isset($_GET['categoryId']) && is_numeric($_GET['categoryId']) && $_GET['categoryId'] > 0){
                try {
                    $postModel = new PostModel();
                    $arrPost = $postModel->getPostByCategoryId($_GET['categoryId']);

                    foreach($arrPost as &$post){
                        echo $post['categoryId'];
                        if($post['categoryId'] != ''){
                            $categoryModel = new CategoryModel();
                            $arrCategory = $categoryModel->getCategoriesId($post['categoryId']);
                            $post['category'] = $arrCategory;
                        }
                    }

                    $responseData = json_encode($arrPost);
                } catch (Error $e) {
                    $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            }else{
                try {
                    $intLimit = 10;
                    $postModel = new PostModel();
                    $arrPost = $postModel->getPost($intLimit);
                    $responseData = json_encode($arrPost);
                } catch (Error $e) {
                    $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            }
        } 

        if (strtoupper($requestMethod) == 'POST'){
            try {
                if(!isset($_POST['title'])){
                    $error = array(
                        'Error' => "Title is required."
                    );
                    $this->sendOutput(
                        json_encode($error),
                        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                    );
                }
                if(!isset($_POST['text'])){
                    $error = array(
                        'Error' => "Text is required."
                    );
                    $this->sendOutput(
                        json_encode($error),
                        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                    );
                }
                if(!isset($_POST['categoryId'])){
                    $error = array(
                        'Error' => "Category id is required."
                    );
                    $this->sendOutput(
                        json_encode($error),
                        array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                    );
                }
    
                $postModel = new PostModel();
                $arrPost = $postModel->createPost($_POST['title'],$_POST['text'],$_POST['categoryId']);
                $responseData = json_encode($arrPost);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }
        // else {
        //     $strErrorDesc = 'Method not supported';
        //     $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        // }
 
        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function listId($id)
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'GET') {

            if(!is_numeric($id)){
                $error = array(
                    'Error' => "Id '{$id}' its not numeric."
                );
                $this->sendOutput(
                    json_encode($error),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }

            if($id < 1){
                $error = array(
                    'Error' => "Id cant be less than 1."
                );
                $this->sendOutput(
                    json_encode($error),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }

            try {                    
                $postModel = new PostModel();
                $arrPost = $postModel->getPostId($id);
                
                if($arrPost[0]['categoryId'] != ''){
                    $categoryModel = new CategoryModel();
                    $arrCategory = $categoryModel->getCategoriesId($arrPost[0]['categoryId']);
                    $arrPost[0]['category'] = $arrCategory;
                }
                

                $responseData = json_encode($arrPost);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }           
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
 
        // send output
        if (!$strErrorDesc) {
            if($responseData != '[]'){
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }else{
                $error = array(
                    'Error' => 'Post not found.'
                );
                $this->sendOutput(
                    json_encode($error),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}