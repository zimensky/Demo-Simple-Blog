<?php
class Article extends AppModel
{
    const POSTS_PER_PAGE = 5;

    private $_id;
    private $_title;
    private $_intro;
    private $_text;
    private $_createdon;
    private $_published;
    private $_authorId;
    private $_authorName;

    public function getArticles($pageNumber = 1)
    {
//        $sql = "SELECT * FROM blog ORDER BY createdon DESC";
        $sql = "SELECT b.id, b.title, b.intro, b.text, b.createdon, b.published, u.username as author
                FROM blog as b
                LEFT JOIN user as u ON b.author_id = u.id";

        $sql .= " ORDER BY b.createdon DESC";

        $pageNumber = ($pageNumber - 1) * self::POSTS_PER_PAGE;
        $sql .= " LIMIT ".$pageNumber.", ".self::POSTS_PER_PAGE;

        $this->setSql($sql);
        $articles = $this->getAll();
        return ($articles) ? $articles : false;
    }

    /**
     * Get articles specified by user
     * @return array|bool
     */
    public function getArticlesByUser($userId, $pageNumber = 1)
    {
        $sql = "SELECT b.id, b.title, b.intro, b.text, b.createdon, b.published, u.username as author
                FROM blog as b
                LEFT JOIN user as u ON b.author_id = u.id";

        if(!empty($userId)) $sql .= " WHERE b.author_id = ?";

        $sql .= " ORDER BY b.createdon DESC";

        $pageNumber = ($pageNumber - 1) * self::POSTS_PER_PAGE;
        $sql .= " LIMIT ".$pageNumber.", ".self::POSTS_PER_PAGE;

        $this->setSql($sql);
        $articles = $this->getAll(array($userId));
        return ($articles) ? $articles : false;
    }

    public function getPublishedArticles($pageNumber = 1)
    {
        $sql = "SELECT b.id, b.title, b.intro, b.text, b.createdon, b.published, b.author_id, u.username as author
                FROM blog as b
                LEFT JOIN user as u ON b.author_id = u.id
                WHERE b.published = 1 ORDER BY b.createdon DESC";
        $pageNumber = ($pageNumber - 1) * self::POSTS_PER_PAGE;
        $sql .= " LIMIT ".$pageNumber.", ".self::POSTS_PER_PAGE;

        $this->setSql($sql);
        $articles = $this->getAll();
        return ($articles) ? $articles : false;
    }

    public function getArticlesCount($userId = '')
    {
        $sql = "SELECT count(id) as count FROM blog";
        if(!empty($userId))
            $sql .= " WHERE author_id = ".(int)$userId;

        $this->setSql($sql);
        $count = $this->getRow();
        return $count['count'];
    }

    public function getPublishedArticlesCount()
    {
        $sql = "SELECT count(id) as count FROM blog WHERE published = 1";
        $this->setSql($sql);
        $count = $this->getRow();
        return $count['count'];
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function getArticleById($id)
    {
        $sql = "SELECT b.id, b.title, b.intro, b.text, b.createdon, b.published, b.author_id, u.username as author
                FROM blog as b
                LEFT JOIN user as u ON b.author_id = u.id
                WHERE b.id = ?";
        $this->setSql($sql);
        $article = $this->getRow(array($id));
        if($article)
        {
            $this->isNewRecord = false;
            $this->_id = $article['id'];
            $this->_title = $article['title'];
            $this->_intro = $article['intro'];
            $this->_text = $article['text'];
            $this->_createdon = $article['createdon'];
            $this->_published = $article['published'];
            $this->_authorId = $article['author_id'];
            $this->_authorName = $article['author'];
            return true;
        }
        return false;
    }

    public function deleteSelf()
    {
        if(empty($this->_id)) return false;

        $sql = "DELETE FROM blog WHERE id = ?";
        $this->setSql($sql);
        return $this->query(array($this->_id));
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($value)
    {
        $this->_title = $value;
    }

    public function getIntro()
    {
        return $this->_intro;
    }

    public function setIntro($value)
    {
        $this->_intro = $value;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function setText($value)
    {
        $this->_text = $value;
    }

    public function getCreateDate()
    {
        return $this->_createdon;
    }

    public function setCreateDate($value)
    {
        $this->_createdon = $value;
    }

    public function getStatus()
    {
        return $this->_published;
    }

    public function setStatus($value)
    {
        $this->_published = $value ? 1 : 0;
    }

    public function getAuthorId()
    {
        return $this->_authorId;
    }

    public function setAuthorId($value)
    {
        $this->_authorId = $value;
    }

    public function getAuthorName()
    {
        return $this->_authorName;
    }

    public function save()
    {
        if($this->isNewRecord)
        {
            $sql = "INSERT INTO blog (title, intro, text, createdon, published, author_id) values(:title, :intro, :text, :createdon, :published, :author_id)";
            $this->setSql($sql);
            $res = $this->query(array(
                ':title' => $this->_title,
                ':intro' => $this->_intro,
                ':text' => $this->_text,
                ':createdon' => $this->_createdon,
                ':published' => $this->_published,
                ':author_id' => $this->_authorId
            ));
        }
        else
        {
            $sql = "UPDATE blog SET title = :title, intro = :intro, text = :text, published = :published WHERE id = :id";
            $this->setSql($sql);
            $res = $this->query(array(
                ':id' => $this->_id,
                ':title' => $this->_title,
                ':intro' => $this->_intro,
                ':text' => $this->_text,
                ':published' => $this->_published
            ));
        }
        return $res ? true : false;
    }
}
