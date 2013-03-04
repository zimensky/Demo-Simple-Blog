<?php
class AppModel
{
    protected $db;
    protected $sql;
    public $isNewRecord = true;

    public function __construct()
    {
        $this->db = DB::init();
    }

    protected function setSql($sql)
    {
        $this->sql = $sql;
    }

    public function getAll($data = null)
    {
        if(!$this->sql)
            throw new Exception('No SQL query!');

        $sth = $this->db->prepare($this->sql);
        $sth->execute($data);
        return $sth->fetchAll();
    }

    public function getRow($data = null)
    {
        if(!$this->sql)
            throw new Exception('No SQL query!');

        $sth = $this->db->prepare($this->sql);
        $sth->execute($data);
        return $sth->fetch();
    }

    public function query($data = null)
    {
        if(!$this->sql)
            throw new Exception('No SQL query!');

        $sth = $this->db->prepare($this->sql);
        $res = $sth->execute($data);
        return $res;
    }

}
