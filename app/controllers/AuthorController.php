<?php
class AuthorController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Author');
        $this->setView('index');
    }

    public function index($pageNumber = 1)
    {
        if(!$this->user->hasPermission('readPages'))
        {
            Route::redirect('login');
            return true;
        }

        $authors = array();

        $a = new Author;
        $authorsList = $a->getAuthorsList();

        if(!empty($authorsList))
        {
            foreach($authorsList as $author)
            {
                $a->setId($author['id']);
                $authors[] = array(
                    'id' => $author['id'],
                    'username' => $author['username'],
                    'postsCount' => $author['count'],
                    'lastPosts' => $a->getAuthorPosts(5)
                );
            }
        }

        $this->view->set(array(
            'pageHeader' => 'Authors in blog',
            'authors' => $authors,
        ));

        echo $this->view->outputCombine();
    }

}
