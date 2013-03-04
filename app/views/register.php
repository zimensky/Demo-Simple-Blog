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
            <h1>Register user account</h1>
        </div>
        <form action="/register" method="POST">
            <div class="message"><?php echo $message; ?></div>
            <div class="error-message"><?php echo $errorMessage; ?></div>
            <div class="errors-list">
                <ul>
                <?php if($errors) foreach($errors as $e): ?>
                    <li><?php echo $e; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <label>Email:</label>
            <input type="text" name="email" value="<?php echo $email; ?>" />
            <br/>
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo $username; ?>" />
            <br/>
            <label>Password:</label>
            <input type="password" name="password" value="<?php echo $password; ?>" />
            <br/>
            <hr/>
            <input type="submit" name="register" value="Register" />
        </form>
    </div>

    <?php include_once ROOT.'/app/includes/footer.inc.php'; ?>

</div>
</body>
</html>
