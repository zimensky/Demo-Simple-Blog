<?php
class User extends AppModel
{
    const ERROR_NONE = '';
    const ERROR_USERNAME_EMPTY = 'Username can\'t be blank.';
    const ERROR_PASSWORD_EMPTY = 'Password can\'t be blank.';
    const ERROR_EMAIL_EMPTY = 'Email can\'t be blank.';
    const ERROR_USER_ALREADY_EXIST = 'User with such username already exists.';

    const DEFAULT_USER_ROLE = 2;

    protected  $_id;
    protected  $_username;
    private $_password;
    private $_email;
    private $_active = 0;
    protected $_roles = null;

    public function findByUsername($username, $activeOnly = false)
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        if($activeOnly)
            $sql .= " AND active = 1";
        $this->setSql($sql);
        $row = $this->getRow(array($username));
        if($row)
        {
            $this->isNewRecord = false;
            $this->_password = $row['password'];
            $this->_id = $row['id'];
            $this->_username = $row['username'];
            $this->_email = $row['email'];
            $this->_active = $row['active'];

            $this->_initRoles();

            return true;
        }
        return false;
    }

    /**
     * Checks whether user exist
     * @param $username
     * @param $email
     * @return bool|mixed
     */
    public function findUser($username, $email)
    {
        $sql = "SELECT * FROM user WHERE username = :username AND email = :email";
        $this->setSql($sql);
        $user = $this->getRow(array($username, $email));
        return ($user) ? $user : false;
    }

    public function getUserList($pageNumber = 1)
    {
        $sql = "SELECT u.id, u.username, u.email, u.active, GROUP_CONCAT(r.title) as title
            FROM user as u
            LEFT JOIN user_role as ur ON ur.user_id = u.id
            LEFT JOIN role as r ON ur.role_id = r.id
            GROUP BY u.id";
        $pageNumber = ($pageNumber - 1) * Article::POSTS_PER_PAGE;
        $sql .= " LIMIT ".$pageNumber.", ".Article::POSTS_PER_PAGE;

        $this->setSql($sql);
        $users = $this->getAll();
        return ($users)? $users : false;
    }

    public function getUsersCount()
    {
        $sql = "SELECT count(id) as count FROM user";
        $this->setSql($sql);
        $row = $this->getRow();
        return $row['count'];
    }

    public function hasPermission($permission)
    {
        foreach($this->_roles as $role)
        {
            if($role->hasPermission($permission))
                return true;
        }
        return false;
    }

    private function _initRoles()
    {
        $this->_roles = array();

        $sql = "SELECT r.id, r.title
            FROM user_role as ur
            RIGHT JOIN role as r ON ur.role_id = r.id
            WHERE ur.user_id = :user OR r.id= :defaultRole OR r.id = :guestRole";
        $this->setSql($sql);
        $roles = $this->getAll(array(
            ':user' => $this->_id,
            ':defaultRole' => self::DEFAULT_USER_ROLE,
            ':guestRole' => UserGuest::GUEST_ROLE_ID
        ));

        foreach($roles as $userRole)
        {
            $role = new Role;
            $role->setId($userRole['id']);
            $this->_roles[$userRole['title']] = $role;
        }
    }

    public function getRoles()
    {
        if($this->_roles == null)
            $this->_initRoles();
        return $this->_roles;
    }

    public function getRolesIds()
    {
        $roles = $this->getRoles();
        $rolesIds = array();
        if(!empty($roles))
        {
            foreach($roles as $role)
                $rolesIds[] = $role->getId();
        }
        return $rolesIds;
    }

    public function setRoles($rolesIds)
    {
        $this->resetRoles();

        if(!is_array($rolesIds)) return false;
        if(empty($rolesIds)) return false;

        foreach($rolesIds as $roleId)
        {
            $role = new Role;
            $role->insertUserRole($this->_id, $roleId);
            $role->setId((int)$roleId);
            $this->_roles[] = $role;
        }
    }

    public function resetRoles()
    {
        $sql ="DELETE FROM user_role WHERE user_id = ?";
        $this->setSql($sql);
        if($this->query(array($this->_id)))
        {
            $this->_roles = array();
            return true;
        }
        return false;
    }

    public function getPermissions()
    {
        $perm = array();
        $roles = $this->getRoles();
        foreach($roles as $role)
        {
            $perm[] = $role;
        }
//        $perm = array_unique($perm);
        return $perm;
    }
    public function save()
    {
        if(!$this->validate()) return false;

        if($this->isNewRecord)
        {
            $sql = "INSERT INTO user values(null, :username, :password, :email, :active)";
            $this->setSql($sql);
            $res = $this->query(array(
                ':username' => $this->_username,
                ':password' => $this->_password,
                ':email' => $this->_email,
                ':active' => $this->_active
            ));
        }
        else
        {
            $sql = "UPDATE user SET username = :username, email = :email, password = :password, active = :active WHERE id = :id";
            $this->setSql($sql);
            $res = $this->query(array(
                ':id' => $this->_id,
                ':username' => $this->_username,
                ':password' => $this->_password,
                ':email' => $this->_email,
                ':active' => $this->_active
            ));
        }
        return $res ? true : false;
    }

    public function validate()
    {
        if(!$this->isNewRecord && empty($this->_id)) return false;
        if(empty($this->_username)) return false;
        if(empty($this->_password)) return false;
        if(!filter_var($this->_email, FILTER_VALIDATE_EMAIL)) return false;
        if(!in_array($this->_active, array(0, 1))) return false;

        return true;
    }

    public function delete($userId)
    {
        if(empty($userId)) return false;

        $sql = "DELETE FROM user WHERE id = :userId; ";
        $sql .= "DELETE FROM user_role WHERE user_id = :userId; ";
        $sql .= "UPDATE blog SET published = 0 WHERE author_id = :userId; ";
        $this->setSql($sql);
        return $this->query(array(':userId'=>$userId));
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getUsername()
    {
        return $this->_username;
    }

    public function setUsername($username)
    {
        $this->_username = $username;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword($password)
    {
        $this->_password = $this->hashPassword($password);
    }

    public function getStatus()
    {
        return $this->_active;
    }

    public function setStatus($active)
    {
        $this->_active = $active ? 1 : 0;
    }

    public function sendVerificationMail()
    {
        if(empty($this->_email) || empty($this->_password) || empty($this->_username))
            return false;

        $verificationUrl = 'http://'.SITE_URL.'/site/verify/'.$this->_username.'/'.$this->verificationCode();
        $view = new AppView('_registerVerification');
        $view->set('verificationUrl', $verificationUrl);
        $message = $view->output();

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        if(mail($this->_email, 'Подтверждение регистрации на сайте SiteName', $message, $headers))
            return true;
        else
            return false;
    }
    /**
     * Validates password
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        /*echo 'User pass: '.$password.
            '<br>DB pass: '.$this->_password.
            '<br>Batman hash: '.$this->hashPassword('batman').
            '<br>Crypt: '.crypt($password, $this->_password);*/
        return crypt($password, $this->_password)===$this->_password;
    }

    public function verificationCode()
    {
        return crypt($this->_password, $this->_username);
    }
    /**
     * Generate hash of password
     * @param $password
     * @return string Password's hash
     */
    public function hashPassword($password)
    {
        return crypt($password, $this->blowfishSalt());
    }

    /**
     * Generate a random salt in the crypt(3) standard Blowfish format.
     *
     * @param int $cost Cost parameter from 4 to 31.
     *
     * @throws Exception on invalid cost parameter.
     * @return string A Blowfish hash salt for use in PHP's crypt()
     */
    public static function blowfishSalt($cost = 13)
    {
        if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
            throw new Exception("cost parameter must be between 4 and 31");
        }
        $rand = array();
        for ($i = 0; $i < 8; $i += 1) {
            $rand[] = pack('S', mt_rand(0, 0xffff));
        }
        $rand[] = substr(microtime(), 2, 6);
        $rand = sha1(implode('', $rand), true);
        $salt = '$2a$' . str_pad((int) $cost, 2, '0', STR_PAD_RIGHT) . '$';
        $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
        return $salt;
    }
}
