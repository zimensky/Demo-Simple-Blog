<?php
class RoleController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Role');
        $this->setView('manage');
    }

    public function manage($pageNumber = 1)
    {
        if(!$this->user->hasPermission('editRoles'))
        {
            Route::redirect('error403');
            return true;
        }

        if(isset($_POST['saveRoles']))
        {
            $newPermissions = array();
            if(!empty($_POST['hasPermission']))
            {
                foreach($_POST['hasPermission'] as $checkbox)
                    $newPermissions[] = $checkbox;
                if($this->model->updateRules($newPermissions))
                    $this->view->set('message', 'Access rules successfully saved.');
                else
                    $this->view->set('errorMessage', 'Not able to save new rules.');
            }
        }

        $rolesList = $this->model->getRolesList();
        $rolesTitles = array();
        $roles = array();
        foreach($rolesList as $role)
        {
            $rolesTitles[$role['id']] = $role['title'];
            $roleObj = new Role;
            $roleObj->setId($role['id']);
            $roles[] = $roleObj;
        }

        $actionsList = $this->model->getPermissionsList();
        $actions = array();
        foreach($actionsList as $action)
            $actions[$action['name']] = $action['title'];

        $this->view->set(array(
            'pageTitle' => 'Access rules',
            'pageHeader' => 'Manage roles',
            'rolesTitles' => $rolesTitles,
            'roles' => $roles,
            'actions' => $actions
        ));

        echo $this->view->outputCombine();
    }

    public function create()
    {
        if(!$this->user->hasPermission('editRoles'))
        {
            Route::redirect('error403');
            return true;
        }

        if(isset($_POST['addRole']))
        {
            $roleName = htmlspecialchars($_POST['name']);
            if(!empty($roleName))
            {
                $this->model->insertRole($roleName);
                /*if($this->model->insertRole($roleName))
                    $this->view->set('message', 'New role successfully added.');
                else
                    $this->view->set('errorMessage', 'Not able to add new role.');*/
            };
        }

        Route::redirect('role/manage');
    }

    public function delete($roleId)
    {
        if(!$this->user->hasPermission('editRoles'))
        {
            Route::redirect('error403');
            return true;
        }

        // Set no opportunity to delete default and guest's roles
        if($roleId != User::DEFAULT_USER_ROLE && $roleId != UserGuest::GUEST_ROLE_ID)
        {
            if(!empty($roleId))
            {
                $roleId = (int)$roleId;
                $this->model->deleteRole($roleId);
                /*if($this->model->insertRole($roleName))
                    $this->view->set('message', 'New role successfully added.');
                else
                    $this->view->set('errorMessage', 'Not able to add new role.');*/
            }
        }

        Route::redirect('role/manage');
    }
}
