<h1><?php echo $pageHeader; ?></h1>

    <p><?php echo $message; ?></p>
    <p class="error-message"><?php echo $errorMessage; ?></p>

<p>
    <h2>Add new role</h2>
    <form action="/role/create" method="POST">
        New role name: <input type="text" name="name" /><input type="submit" name="addRole" value="Add this role" />
    </form>
    <hr/>
</p>

<h2>Access rules</h2>
<p>
    <b>Guest</b> role is for non-authenticated users.<br/>
    <b>User</b> role is default role for <u>all</u> authenticated users.<br/>
    These two roles <u>can't be deleted</u> and their permissions are inherited to <u>all</u> other user roles.
</p>

<p>
<form action="/role/manage" method="POST">
    <table>
        <thead>
            <tr>
                <th>Permission</th>
                <?php foreach($rolesTitles as $id => $title): ?>
<!--                    <th><a href="/role/edit/--><?php //echo $id; ?><!--">--><?php //echo $title; ?><!--</a></th>-->
                    <th><?php echo $title; ?>
                        <br/><a href="/role/delete/<?php echo $id; ?>"
                                class="delete"
                                onClick="if(!confirm('This action has no rollback. Delete this role?')) return false;"
                                >delete</a></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>

            <?php foreach($actions as $actionName => $actionTitle): ?>
            <tr>
                <td><?php echo $actionTitle; ?></td>
                <?php foreach($roles as $role): ?>
                    <td><input type="checkbox"
                               name="hasPermission[]"
                                <?php if($role->hasPermission($actionName)) echo 'checked' ?>
                                value="<?php echo $role->getId().','.$actionName; ?>"
                        /></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <hr/>
    <input type="submit" name="saveRoles" value="Save" />
</form>
</p>