<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class PostModel extends Database
{
    public function getPost($limit)
    {
        return $this->select("SELECT * FROM post ORDER BY id DESC LIMIT ?", ["i", $limit]);
    }

    public function getPostId($id)
    {
        return $this->select("SELECT * FROM post WHERE id = ?", ["i", $id]);
    }

    public function getPostByCategoryId($id){
        return $this->select("SELECT * FROM post WHERE categoryId = ?", ["i", $id]);
    }

    public function createPost($title, $text, $category){
        $date = date("Y-m-d H:i:s");
        return $this->insert("INSERT INTO post(title, contents, categoryId, timestamp) 
            VALUES(
                ?, ?, ?, ?
            )", [$title, $text, $category, $date]);
    }
}
