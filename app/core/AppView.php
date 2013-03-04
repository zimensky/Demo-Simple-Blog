<?php
class AppView
{
    protected $file;
    protected $data = array();
    protected $viewsPath = 'app/views/';
    protected $generalView = 'app/views/index.php';
    private $_permissions = array();

    public function __construct($file, $ext = 'php')
    {
        $this->file = $this->viewsPath.$file.'.'.$ext;

        if(isset($_SESSION['user']['username']))
        {
            $this->user = new User;
            if($this->user->findByUsername($_SESSION['user']['username']))
                $this->_permissions = $this->user->getPermissions();
        }
        else
        {
            $this->user = new UserGuest;
            $this->_permissions = $this->user->getPermissions();
        }
    }

    public function isAllow($action)
    {
        foreach($this->_permissions as $role)
            if($role->hasPermission($action)) return true;
        return false;
    }

    public function set($key, $value = '')
    {
        if(is_array($key))
        {
            foreach($key as $k => $v)
                $this->data[$k] = $v;
        }
        else
            $this->data[$key] = $value;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : false;
    }

    public function output()
    {
        if(!file_exists($this->file))
            throw new Exception('Template <b>'.$this->file.'</b> not found.');

        extract($this->data);

        ob_start();
        include $this->file;
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    public function outputCombine()
    {
        if(!file_exists($this->file))
            throw new Exception('Template <b>'.$this->file.'</b> not found.');

        extract($this->data);

        // Render partial template
        ob_start();
        include $this->file;
        $mainContent = ob_get_contents();
        ob_end_clean();

        // Render general template
        ob_start();
        include $this->generalView;
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

}
