<?php
class Auth
{
    const ERROR_NONE = '';
    const ERROR_USERNAME_INVALID = 'Username invalid';
    const ERROR_PASSWORD_INVALID = 'Password invalid';

    private $_username;
    private $_password;
    private $_errorCode;

    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }

    public function authenticate()
    {
        $user = new User;
        if($user->findByUsername($this->_username, true))
        {
            if($user->validatePassword($this->_password))
            {
                $this->_errorCode = self::ERROR_NONE;
            }
            else
            {
                $this->_errorCode = self::ERROR_PASSWORD_INVALID;
            }
        }
        else
        {
            $this->_errorCode = self::ERROR_USERNAME_INVALID;
        }

        return $this->_errorCode==self::ERROR_NONE;
    }

    public function login()
    {
        if($this->authenticate())
        {
            $_SESSION['user']['username'] = $this->_username;
            return true;
        }
        else {
            return false;
        }

    }

}
