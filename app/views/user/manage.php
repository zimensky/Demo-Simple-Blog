<h1>Manage users here you welcome!</h1>

    <?php if($userList): ?>

    <p><a href="/user/create">Add new user</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Role</th>
                <th>ops</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach($userList as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><a href="/user/edit/<?php echo $user['username']; ?>"><?php echo $user['username']; ?></a></td>
                <td id="email<?php echo $user['id'] ?>" class="editable"><?php echo $user['email']; ?></td>
                <td><?php echo ($user['active'] == 1) ? 'verified' : 'not verified'; ?></td>
                <td><?php echo $user['title']; ?></td>
                <td><a href="/user/delete/<?php echo $user['id']; ?>"
                       class="delete"
                       onClick="if(!confirm('This action has no rollback. Delete this user?')) return false;"
                       >delete</a></td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <div class="paginate"><?php echo $paginate ?></div>

    <? else: ?>

    <p><?php echo $message; ?></p>
    <p class="error-message"><?php echo $errorMessage; ?></p>

    <form action="/user/<?php echo $formAction; ?>" method="POST">
        <label>Username:</label>

        <?php if($isNewRecord): ?>
            <input type="text" name="username" value="<? echo $username; ?>" />
        <?php else: ?>
            <span><? echo $username; ?></span>
        <?php endif; ?>

        <br/>

        <?php if($isNewRecord): ?>
            <label>Password:</label>
        <?php else: ?>
            <label>New password:</label>
        <?php endif; ?>

        <input type="text" name="password" value="<? echo $password; ?>" />

        <?php if(!$isNewRecord): ?>
            <span class="formfield-info">(Fill only in case of password reset)</span>
        <?php endif; ?>

        <br/>
        <label>Email:</label>
        <input type="text" name="email" value="<? echo $email; ?>" />
        <br/>
        <label>Status:</label>
        <?php echo Html::dropList(array(
            'source' => array(1 => 'Verified', 0 => 'Not verified'),
            'name' => 'active',
            'active' => $active
        ));?>
        <br/>
        <label>Roles:</label>
        <?php if(!empty($rolesList)): ?>
            <?php foreach($rolesList as $role): ?>
                <input type="checkbox"
                       name="roles[]"
                       value="<?php echo $role['id']; ?>"
                       <?php if(!empty($userRoles) && in_array($role['id'], $userRoles)) echo 'checked'; ?>
                        /> <?php echo $role['title']; ?> <br/>
            <?php endforeach; ?>
        <?php endif; ?>
        <br/>
        <hr/>
        <input type="submit" name="saveUser" value="Save" />
    </form>



    <? endif; ?>

<script>

</script>