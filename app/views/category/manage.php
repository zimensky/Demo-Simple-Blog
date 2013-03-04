<h1><?php echo $pageHeader; ?></h1>

<p><?php echo $message; ?></p>
<p class="error-message"><?php echo $errorMessage; ?></p>

<p>
<h2>Add new category</h2>
<form action="/category/create" method="POST">
    New category name: <input type="text" name="name" /><input type="submit" name="addCategory" value="Add category" />
</form>
<hr/>
</p>

<?php if($userList): ?>
<?php else: ?>
<?php endif; ?>