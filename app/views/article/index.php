<h1><?php echo $pageHeader; ?></h1>

<?php
    if($articles):
        foreach($articles as $a):
?>
            <article>
                <header>
                    <h1><a href="/blog/read/<?php echo $a['id']; ?>"><?php echo $a['title']; ?></a></h1>
                    <p><?php echo strftime('%d.%m.%Y %H:%M', $a['createdon']); ?>
                        @ <a href="/blog/author/<?php echo $a['author']; ?>"><?php echo $a['author']; ?></a></p>
                </header>
                <p><?php echo htmlspecialchars_decode($a['intro']); ?></p>
                <p class="readmore"><a href="/blog/read/<?php echo $a['id']; ?>">Read more</a></p>
            </article>
<?php endforeach; ?>

        <div class="paginate"><?php echo $paginate ?></div>

<?php else: ?>
        <h1>Oops!</h1>
        <p>No posts here.</p>
 <?php endif; ?>