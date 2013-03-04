<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <link type="text/css" rel="stylesheet" href="/app/css/default.css" />
</head>
<body>
<div id="wrapper">
    <?php include_once 'app/includes/header.inc.php'; ?>

    <div class="cleaner">
    <div id="left">left more k js ljsdo fisdu sdoifu</div>
    <div id="right">
    <?php
        if($articles):
            foreach($articles as $a):
    ?>
                <article>
                    <header>
                        <h1><a href="/blog/read/<?php echo $a['id']; ?>"><?php echo $a['title']; ?></a></h1>
                        <p><?php echo strftime('%d.%M.%Y %H:%M', $a['createdon']); ?></p>
                    </header>
                    <p><?php echo $a['intro']; ?></p>
                    <p class="readmore"><a href="/blog/read/<?php echo $a['id']; ?>">Читать дальше</a></p>
                </article>
    <?php
            endforeach;
        else:
    ?>
            <h1>Oops!</h1>
            <p>Записей нет.</p>

    <?php endif; ?>
    </div>
    </div>
    <?php include_once 'app/includes/footer.inc.php'; ?>
</div>
</body>
</html>