<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <link type="text/css" rel="stylesheet" href="app/css/default.css" />
</head>
<body>
<div id="wrapper">
    <?php include_once 'app/includes/header.inc.php'; ?>

    <?php echo $mainContent; ?>

    <?php include_once 'app/includes/footer.inc.php'; ?>
</div>
</body>
</html>