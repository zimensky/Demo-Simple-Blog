<?php
class SiteController extends AppController
{
    public function index()
    {
        if(!$this->user->hasPermission('readPages'))
        {
            Route::redirect('login');
            return true;
        }

        $this->blog();
        /*$view = new AppView('index');
        $view->set('pageTitle', 'Home');
        $view->set('mainContent', 'Home');
        echo $view->output();*/
    }

    public function blog()
    {
        if(!$this->user->hasPermission('readPages'))
        {
            Route::redirect('login');
            return true;
        }

        $controller = new BlogController();
        $controller->index();
    }

    public function author()
    {
        if(!$this->user->hasPermission('readPages'))
        {
            Route::redirect('login');
            return true;
        }

        $controller = new AuthorController();
        $controller->index();

    }

    public function contact()
    {
        if(!$this->user->hasPermission('readPages'))
        {
            Route::redirect('login');
            return true;
        }

        $view = new AppView('index');
        $view->set('pageTitle', 'Contact');
        $view->set('mainContent', 'Contact');
        echo $view->output();
    }

    public function login()
    {
        $error = false;

        if(isset($_POST['login']))
        {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            if(empty($username) || empty($password)){
                $error = true;
            }
            else
            {
                $auth = new Auth($username, $password);
                if($auth->login())
                {
                    Route::redirect('index');
                }
                else
                    $error = true;
            }
        }

        $view = new AppView('login');
        $view->set('pageTitle', 'Authentication');
        $view->set('username', $username);
        $view->set('password', $password);
        if($error)
            $view->set('errorMessage', 'Wrong login/password or email not verified.');
        echo $view->output();
    }

    public function logout()
    {
        unset($_SESSION['user']);
        Route::redirect('index');
    }

    public function register()
    {
        $view = new AppView('register');
        $view->set('pageTitle', 'Registration');

        $errors = array();
        if(isset($_POST['register']))
        {
            $email = htmlspecialchars($_POST['email']);
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            if(empty($email))
                $errors[] = User::ERROR_EMAIL_EMPTY;
            if(empty($username))
                $errors[] = User::ERROR_USERNAME_EMPTY;
            if(empty($password))
                $errors[] = User::ERROR_PASSWORD_EMPTY;

            $user = new User;
            if($user->findByUsername($username))
                $errors[] = User::ERROR_USER_ALREADY_EXIST;

            if(sizeof($errors)==0)
            {
                $user->setEmail($email);
                $user->setUsername($username);
                $user->setPassword($password);
                if($user->save())
                {
                    if($user->sendVerificationMail())
                        $view->set('message', 'Registration is almost complete, the mail was sent to your email. Redirecting to login page now...');
                    else
                        $view->set('message', 'Registration is almost complete, but the verification mail wasn\'t sent due an error. Redirecting to login page now...');
                    Route::redirect('index', 4);
                }
                else
                {
                    $view->set('errorMessage', 'Not able to save user information. Can\'t continue registration.');
                }
            }
        }

        if(sizeof($errors)>0)
        {
            $view->set('errorMessage', 'Next errors are found:');
            $view->set('errors', $errors);
        }

        $view->set('email', $email);
        $view->set('username', $username);
        $view->set('password', $password);

        echo $view->output();
    }

    public function verify($code)
    {
        $code = explode('/', $code, 2);
        $username = htmlspecialchars($code[0]);
        $verification = htmlspecialchars($code[1]);

        $user = new User;
        if($user->findByUsername($username))
        {
            if($verification == $user->verificationCode())
            {
                $user->setStatus(1);
                if($user->save())
                    $message = 'The email was successfully confirmed! Now you can to log in.';
                else
                    $errorMessage = 'Can\t update user information.';
            }
            else
                $errorMessage = 'Wrong confirmation code.';
        }
        else
            $errorMessage = 'User not found.';

        $view = new AppView('login');
        $view->set('pageTitle', 'Authentication');
        $view->set('message',$message);
        $view->set('errorMessage',$errorMessage);
        echo $view->output();
    }

    public function requestVerify()
    {
        $view = new AppView('requestVerify');
        $view->set('pageTitle', 'Requesting confirmation email');
        echo $view->output();
    }

    public function error403()
    {
        $view = new AppView('error404');
        $view->set('pageTitle', 'Access denied');
        $view->set('mainContent', 'You are not authorized to do that.');
        echo $view->output();
    }

    public function error404()
    {
        $view = new AppView('error404');
        $view->set('pageTitle', 'Page not found');
        $view->set('mainContent', 'Page not found.');
        echo $view->output();
    }
}
