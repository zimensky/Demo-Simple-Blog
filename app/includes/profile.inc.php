<?php if(!empty($_SESSION['user']['username'])): ?>

    <span id="profile-username"><?php echo $_SESSION['user']['username'];?></span>
    <span id="logout"> (<a href="/logout">Logout</a>)</span>

<?php else: ?>

    <form action="/login" method="POST">
        <div class="message"><?php echo $message; ?></div>
        <div class="error-message"><?php echo $errorMessage; ?></div>
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $username; ?>" />
        <br/>
        <label>Password:</label>
        <input type="password" name="password" value="<?php echo $password; ?>" />
        <br/>
        <hr/>
        <input type="submit" name="login" value="Log in" /> or <a href="/register">Register</a>
    </form>

<?php endif; ?>