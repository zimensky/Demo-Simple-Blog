<?php
class Role extends AppModel
{
    private $_id;
    protected $permissions = null;

    public function getId()
    {
        return $this->_id;
    }

    private function _initPermissions()
    {
        $this->permissions = array();

        $sql = "SELECT p.name
            FROM role_permission as rp
            JOIN permission as p ON rp.permission_id = p.id
            WHERE role_id = ?";
        $this->setSql($sql);
        $rolePermissions = $this->getAll(array($this->_id));

        foreach($rolePermissions as $perm)
        {
            $this->permissions[$perm['name']] = true;
        }
    }

    public function getPermissions()
    {
        if($this->permissions == null)
            $this->_initPermissions($this->_id);

        return $this->permissions;
    }

    public function setId($value)
    {
        $this->_id = (int)$value;
    }

    public function hasPermission($permission)
    {
        if($this->permissions == null)
            $this->_initPermissions();
        return isset($this->permissions[$permission]);
    }

    public function insertRole($roleName)
    {
        $sql = "INSERT INTO role (title) VALUES (?)";
        $this->setSql($sql);
        return $this->query(array($roleName));
    }

    public function insertUserRole($userId, $roleId)
    {
        $sql = "INSERT INTO user_role VALUES (:userId, :roleId)";
        $this->setSql($sql);
        $sth = $this->query(array(
            ':userId' => $userId,
            ':roleId' => $roleId
        ));
        return $sth;
    }

    /**
     * @param bool $withoutDefaults If false display all roles, else - with no default roles
     * @return array List of user roles
     */
    public function getRolesList($withoutDefaults = false)
    {
        $sql = "SELECT r.id, r.title FROM role as r";

        if($withoutDefaults)
            $sql .= " WHERE r.id != ".User::DEFAULT_USER_ROLE." AND r.id != ".UserGuest::GUEST_ROLE_ID;

        $this->setSql($sql);
        $roles = $this->getAll();
        return $roles;
    }

    public function getPermissionsList()
    {
        $sql = "SELECT p.id, p.name, p.title, g.title as pgroup
                FROM permission as p, permission_group as g
                WHERE p.group_id = g.id
                ORDER BY g.index, p.index ASC";
        $this->setSql($sql);
        return $this->getAll();
    }

    /**
     * Sets new access rules
     * @param $data Array of pairs "RoleID,PermissionID"
     * @return bool
     */
    public function updateRules($data)
    {
        if(!is_array($data)) return false;

        $permissionsList = $this->getPermissionsList();

        foreach($permissionsList as $permission)
        {
            foreach($data as $id => $rule)
            {
                $data[$id] = str_replace($permission['name'], $permission['id'], $rule);
            }
        }

        $newRules = array();
        foreach($data as $rule)
            $newRules[] = explode(',', $rule);


        $sql = "TRUNCATE TABLE role_permission; ";
        if(!empty($newRules))
        {
            $sql .= "INSERT INTO role_permission (role_id, permission_id) VALUES ";
            $first = true;
            foreach($newRules as $rule)
            {
                $sql .= ($first ? "" : ", ")."(".$rule[0].", ".$rule[1].")";
                $first = false;
            }
        }

        $this->setSql($sql);
        return $this->query();
    }

    public function deleteRole($roleId)
    {
        $sql = "DELETE FROM role WHERE role.id = :roleId; ";
        $sql .= "DELETE FROM user_role WHERE user_role.role_id = :roleId; ";
        $sql .= "DELETE FROM role_permission WHERE role_permission.role_id = :roleId; ";
        $this->setSql($sql);
        return $this->query(array(':roleId'=>$roleId));
    }
}
