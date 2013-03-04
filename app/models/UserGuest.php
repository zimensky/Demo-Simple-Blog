<?php
class UserGuest extends User
{
    const GUEST_ROLE_ID = 1;

    public function __construct()
    {
        parent::__construct();
        $this->_initRoles(self::GUEST_ROLE_ID);
    }

    private function _initRoles($roleId)
    {
        $this->_roles = array();
        $role = new Role;
        $role->setId($roleId);
        $this->_roles['Guest'] = $role;
    }

}
