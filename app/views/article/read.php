<h1><?php echo $title; ?></h1>
<div><?php echo htmlspecialchars_decode($text); ?></div>
<p class="author-sign">
    <span class="article_date"><?php echo $createdon; ?></span>
    @ <a href="/blog/author/<?php echo $authorName; ?>"><?php echo $authorName; ?></a>
</p>