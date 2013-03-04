<?php
class Category extends AppModel
{
    public function insertRow($name)
    {
        $sql = "INSERT INTO category (title) VALUES (?)";
        $this->setSql($sql);
        return $this->query(array($name));
    }

    public function deleteRow($id)
    {
        $sql = "DELETE FROM category WHERE id = :id; ";
        $sql .= "DELETE FROM article_category WHERE category_id = :id; ";
        $this->setSql($sql);
        return $this->query(array(':id'=>$id));
    }
}
