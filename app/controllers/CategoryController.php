<?php
class CategoryController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Category');
        $this->setView('manage');
    }

    public function manage($pageNumber = 1)
    {
        if(empty($pageNumber)) $pageNumber = 1;

        if(!$this->user->hasPermission('editCategory'))
        {
            Route::redirect('error403');
            return true;
        }
        $this->view->set(array(
            'pageTitle' => 'Categories',
            'pageHeader' => 'Manage categories',
        ));
        echo $this->view->outputCombine();
    }

    public function create()
    {
        if(!$this->user->hasPermission('editCategory') && !$this->user->hasPermission('createCategory'))
        {
            Route::redirect('error403');
            return true;
        }

        if(isset($_POST['addCategory']))
        {
            $categoryName = htmlspecialchars($_POST['name']);
            if(!empty($categoryNameName))
            {
                $this->model->insertRow($categoryName);
            }
        }

        Route::redirect('category/manage');
    }

    public function delete($id)
    {
        if(!$this->user->hasPermission('editCategory') && !$this->user->hasPermission('deleteCategory'))
        {
            Route::redirect('error403');
            return true;
        }

        if(!empty($id))
        {
            $id = (int)$id;
            $this->model->deleteRow($id);
        }

        Route::redirect('category/manage');
    }
}
