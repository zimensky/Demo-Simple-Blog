<?php
class Author extends User
{
    /*private $_id;
    private $_username;*/

    /*public function setId($id)
    {
        $this->_id = (int)$id;
    }*/

    /*public function setUsername($username)
    {
        $this->_username = $username;
    }*/

    public function getAuthorsList()
    {
        $sql = "SELECT u.id, u.username, count(b.id) as count
                FROM user as u, blog as b
                WHERE b.author_id = u.id AND u.id IN (
                  SELECT b.author_id FROM blog as b WHERE b.published = 1 GROUP BY b.author_id
                )
                GROUP BY u.id
                ORDER BY count DESC";
        $this->setSql($sql);
        return $this->getAll();
    }

    public function getAuthorPosts($limit = 0)
    {
        $sql = "SELECT b.id, b.title, b.createdon, b.intro, b.text
                FROM blog as b
                WHERE b.published = 1 AND b.author_id = :authorId
                ORDER BY b.createdon DESC";
        if($limit > 0)
            $sql .= " LIMIT ".(int)$limit;

        $this->setSql($sql);
        return $this->getAll(array(
            ':authorId' => $this->_id,
        ));
    }

    public function getAuthorPostsByPage($offset = 1)
    {
        $sql = "SELECT b.id, b.title, b.createdon, b.intro, b.text, u.username as author
                FROM blog as b, user as u
                WHERE b.published = 1 AND b.author_id = :authorId AND u.id = b.author_id
                ORDER BY b.createdon DESC";
        $offset = ($offset - 1) * Article::POSTS_PER_PAGE;
        $sql .= " LIMIT ".$offset.", ".Article::POSTS_PER_PAGE;

        $this->setSql($sql);
        return $this->getAll(array(
            ':authorId' => $this->_id,
        ));
    }

    public function getAuthorPostsCount()
    {
        $sql = "SELECT count(b.id) as count
                FROM blog as b
                WHERE b.published = 1 AND b.author_id = ?";
        $this->setSql($sql);
        $sth = $this->getRow(array($this->_id));
        if($sth)
            $count = $sth['count'];
        else $count = 0;

        return $count;
    }
}
