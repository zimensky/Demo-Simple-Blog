<h1><?php echo $pageHeader; ?></h1>

    <p><?php echo $message; ?></p>
    <p class="error-message"><?php echo $errorMessage; ?></p>

    <?php if($state == 'many'): ?>

    <?php if(!empty($articles)): ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Created on</th>
                <th>Status</th>
                <th>Author</th>
                <th>ops</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach($articles as $article): ?>
            <tr>
                <td><?php echo $article['id']; ?></td>
                <td><a href="/blog/edit/<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a></td>
                <td><?php echo strftime('%d.%m.%Y %H:%M', $article['createdon']); ?></td>
                <td><?php echo ($article['published'] == 1) ? 'published' : 'not published'; ?></td>
                <td>

                    <?php if($this->isAllow('editUser')): ?>
                        <a href="/user/edit/<?php echo $article['author']; ?>"><?php echo $article['author']; ?></a>
                    <?php else: ?>
                        <?php echo $article['author']; ?>
                    <?php endif; ?>

                </td>
                <td>

                    <?php if($this->isAllow('deleteOwnArticle') || $this->isAllow('deleteAllArticles')): ?>
                    <a href="/blog/delete/<?php echo $article['id']; ?>"
                       class="delete"
                       onClick="if(!confirm('This action has no rollback. Delete this post?')) return false;"
                        >delete</a>
                    <?php endif; ?>

                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

    <div class="paginate"><?php echo $paginate ?></div>

    <? else: ?>

        <h2>Oops!</h2>
        <p>You don't make any posts yet.</p>

    <? endif; ?>

    <? elseif($state == 'one'): ?>

    <form action="/blog/<?php echo $formAction; ?>" method="POST" class="fullwidth">
        <input type="hidden" name="articleId" value="<?php echo $articleId; ?>" />

        <label>Title:</label>
        <input type="text" name="title" value="<? echo $title; ?>" />

        <?php if(!$isNewRecord): ?>
        <br/>by <i><? echo $author; ?></i> on <? echo $createdon; ?>
        <?php endif; ?>

        <label>Intro text:</label>
        <textarea name="intro" rows="20"><? echo $intro; ?></textarea>

        <label>Main text:</label>
        <textarea name="text" rows="35"><? echo $text; ?></textarea>

        <label>Status:</label>
        <?php echo Html::dropList(array(
            'source' => array('1'=>'Published','0'=>'Not published'),
            'name' => 'published',
            'active' => $published
            ));
        ?>
        <br/>
        <hr/>
        <input type="submit" name="saveArticle" value="Save" />
    </form>



    <? endif; ?>