<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <link type="text/css" rel="stylesheet" href="/app/css/default.css" />
</head>
<body>
<div id="wrapper">

    <?php include_once ROOT.'/app/includes/header.inc.php'; ?>

    <div id="login-form">
        <div>
            Authorized personnel only. <a href="/register">Register</a>.
        </div>
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
            <input type="submit" name="login" value="Log in" />
        </form>
    </div>

    <?php include_once ROOT.'/app/includes/footer.inc.php'; ?>

</div>
</body>
</html>
