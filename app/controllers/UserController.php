<?php
class UserController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('User');
        $this->setView('manage');
    }

    public function manage($pageNumber = 1)
    {
        if(empty($pageNumber)) $pageNumber = 1;

        if(!$this->user->hasPermission('editUser'))
        {
            Route::redirect('error403');
            return true;
        }

        $model = $this->model;

        $userList = $model->getUserList($pageNumber);

        $paginate = Html::pagination(array(
            'baseUrl' => '/user/manage/',
            'perPage' => Article::POSTS_PER_PAGE,
            'postsCount' => $this->model->getUsersCount(),
            'currentPage' => $pageNumber
        ));

        $this->view->set(array(
            'pageTitle' => 'Users',
            'userList' => $userList,
            'formAction' => 'manage',
            'paginate' => $paginate
        ));
        echo $this->view->outputCombine();
    }

    public function edit($userName)
    {
        if(!$this->user->hasPermission('editUser'))
        {
            Route::redirect('error403');
            return true;
        }

        if(empty($userName))
        {
            Route::redirect('user/manage');
            return false;
        }

        if(isset($_POST['saveUser']))
        {
            //$username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $email = htmlspecialchars($_POST['email']);
            $active = (int)$_POST['active'];
            $userRoles = $_POST['roles'];

            $model = $this->model;
            if($model->findByUsername($userName))
            {
                if(!empty($password))
                    $model->setPassword($password);

                $model->setEmail($email);
                $model->setStatus($active);
                $model->setRoles($userRoles);
                if($model->save())
                    $this->view->set('message', 'Information updated.');
                else
                    $this->view->set('errorMessage', 'Not able to update user info.');
            }
            else
            {
                Route::redirect('user/manage');
                return false;
            }
        }

        $model = $this->model;
        if($model->findByUsername($userName))
        {
            $role = new Role;
            $rolesList = $role->getRolesList(true);
            $userRoles = $this->model->getRolesIds();

            $username = $model->getUsername();
            $email = $model->getEmail();
            $active = $model->getStatus();
            $this->view->set(array(
                'rolesList' => $rolesList,
                'userRoles' => $userRoles
            ));
        }
        else
        {
            Route::redirect('user/manage');
            return false;
        }

        $this->view->set(array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'active' => $active,
            'formAction' => 'edit/'.$username
        ));

        echo $this->view->outputCombine();
    }

    public function create()
    {
        if(!$this->user->hasPermission('createUser'))
        {
            Route::redirect('error403');
            return true;
        }

        if(isset($_POST['saveUser']))
        {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $email = htmlspecialchars($_POST['email']);
            $active = (int)$_POST['active'];
            $userRoles = $_POST['roles'];

            $model = $this->model;
            if($model->findByUsername($username))
            {
                $this->view->set('errorMessage', 'User with the same name is already exist.');
            }
            else
            {
                $newUser = new User;
                $newUser->setUsername($username);
                $newUser->setPassword($password);
                $newUser->setEmail($email);
                $newUser->setStatus($active);
                $newUser->setRoles($userRoles);
                if($newUser->save())
                    $this->view->set('message', 'New user successfully added.');
                else
                    $this->view->set('errorMessage', 'Not able to save user.');
            }
        }

        $role = new Role;
        $rolesList = $role->getRolesList(true);
        $userRoles = $this->model->getRolesIds();

        $this->view->set(array(
            'isNewRecord' => true,
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'active' => $active,
            'rolesList' => $rolesList,
            'formAction' => 'create'
        ));

        echo $this->view->outputCombine();
    }

    public function delete($userId)
    {
        if(!$this->user->hasPermission('deleteUser'))
        {
            Route::redirect('error403');
            return true;
        }

        if(empty($userId))
        {
            Route::redirect('user/manage');
            return false;
        }

        $this->model->delete($userId);

        Route::redirect('user/manage');
        return true;

    }
}
