<h1><?php echo $pageHeader; ?></h1>

<?php if(!empty($authors)): ?>
    <?php foreach($authors as $author): ?>
    <div class="author-item">
        <h2>@<?php echo $author['username']; ?> <a href="/blog/author/<?php echo $author['username']; ?>">Read entries</a></h2>
        <p>Write in total <?php echo $author['postsCount']; ?> posts.</p>
        <?php if(!empty($author['lastPosts'])): ?>
            <p>
                Last of them:
                <ul>
                    <?php foreach($author['lastPosts'] as $post): ?>
                    <li><a href="/blog/read/<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </p>
        <?php endif; ?>
        <hr/>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <h2>Oops!</h2>
    <p>No authors yet.</p>
<?php endif; ?>