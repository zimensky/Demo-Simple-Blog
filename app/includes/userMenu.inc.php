<?php if($this->isAllow('createArticle') || $this->isAllow('editAllArticles') || $this->isAllow('editCategory') || $this->isAllow('editUser')) : ?>

    <h4>Manage</h4>

    <pre><?php //print_r($this->_permissions); ?></pre>

    <ul>
        <?php if($this->isAllow('createArticle')): ?>
            <li><a href="/blog/create">New article</a></li>
        <?php endif; ?>

        <?php if($this->isAllow('editAllArticles') || $this->isAllow('editOwnArticle')): ?>
            <li><a href="/blog/manage">Edit articles</a></li>
        <?php endif; ?>

        <?php if($this->isAllow('editCategory')): ?>
            <li><a href="/category/manage">Edit categories</a></li>
        <?php endif; ?>

        <?php if($this->isAllow('editUser')): ?>
            <li><a href="/user/manage">Edit users</a></li>
        <?php endif; ?>

        <?php if($this->isAllow('editRoles')): ?>
        <li><a href="/role/manage">Edit user roles</a></li>
        <?php endif; ?>
    </ul>

<?php endif; ?>