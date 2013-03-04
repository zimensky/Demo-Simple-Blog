<?php
class AppController
{
    protected $model;
    protected $view;
    protected $modelBaseName;
    protected $user;

    public function __construct()
    {
        if(isset($_SESSION['user']['username']))
        {
            $this->user = new User;
            $this->user->findByUsername($_SESSION['user']['username']);
        }
        else
        {
            $this->user = new UserGuest;
            echo $this->user->hasPermission('asd');
        }
    }

    protected function setModel($model)
    {
        $this->model = new $model;
        $this->modelBaseName = $model;
    }

    protected function setView($view)
    {
        $this->view = new AppView(strtolower($this->modelBaseName).'/'.$view);
    }

    protected function useView($view)
    {
        $this->view = new AppView($view);
    }
}
