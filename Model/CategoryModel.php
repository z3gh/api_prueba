<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class CategoryModel extends Database
{
    public function getCategories($limit)
    {   
        return $this->select("SELECT * FROM categories ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }

    public function getCategoriesId($id)
    {   
        return $this->select("SELECT * FROM categories WHERE id = ?", ["i", $id]);
    }
}