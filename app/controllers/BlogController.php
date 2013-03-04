<?php
class BlogController extends AppController
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel('Article');
        $this->setView('index');
    }

    public function index($pageNumber = 1)
    {
        if(empty($pageNumber)) $pageNumber = 1;

        $articles = $this->model->getPublishedArticles($pageNumber);

        $postsCount = $this->model->getPublishedArticlesCount();

        $paginate = Html::pagination(array(
            'baseUrl' => '/blog/index/',
            'perPage' => Article::POSTS_PER_PAGE,
            'postsCount' => $postsCount,
            'currentPage' => $pageNumber
        ));

        $this->view->set(array(
            'articles' => $articles,
            'pageTitle' => 'Article entries',
            'paginate' => $paginate
        ));

        echo $this->view->outputCombine();
    }

    public function read($params)
    {
        $id = (int)$params;
        $model = $this->model;
        $model->getArticleById($id);
        $this->setView('read');
        $this->view->set(array(
            'pageTitle' => $model->getTitle(),
            'title' => $model->getTitle(),
            'text' => $model->getText(),
            'createdon' => strftime('%d.%m.%Y %H:%M', $model->getCreateDate()),
            'authorName' => $model->getAuthorName()
        ));
        echo $this->view->outputCombine();
    }

    public function author($params)
    {
        if(!$this->user->hasPermission('readPages'))
        {
            Route::redirect('login');
            return true;
        }

        $params = explode('/', $params);
        $authorName = $params[0];
        $pageNumber = !empty($params[1]) ? (int)$params[1] : 1;

        $author = new Author;
        if($author->findByUsername($authorName))
        {
            $posts = $author->getAuthorPostsByPage($pageNumber);

            $paginate = Html::pagination(array(
                'baseUrl' => '/blog/author/'.$authorName.'/',
                'perPage' => Article::POSTS_PER_PAGE,
                'postsCount' => $author->getAuthorPostsCount(),
                'currentPage' => $pageNumber
            ));

//            $this->useView('article/index');
            $this->view->set(array(
                'pageHeader' => 'Posts from '.$author->getUsername(),
                'articles' => $posts,
                'paginate' => $paginate
            ));
            echo $this->view->outputCombine();
        }
        else
        {
            Route::redirect('blog/author');
            return false;
        }
    }

    public function manage($pageNumber = 1)
    {
        if(empty($pageNumber)) $pageNumber = 1;

        if(!$this->user->hasPermission('editAllArticles') && !$this->user->hasPermission('editOwnArticle'))
        {
            Route::redirect('error403');
            return true;
        }

        $this->setView('manage');

        $articles = array();
        if(!$this->user->hasPermission('editAllArticles'))
            $articles = $this->model->getArticlesByUser($this->user->getId(), $pageNumber);
        else
            $articles = $this->model->getArticles($pageNumber);

        $paginate = Html::pagination(array(
            'baseUrl' => '/blog/manage/',
            'perPage' => Article::POSTS_PER_PAGE,
            'postsCount' => $this->model->getArticlesCount(),
            'currentPage' => $pageNumber
        ));

        $this->view->set('pageTitle', 'Manage articles');
        $this->view->set('pageHeader', 'Manage articles');
        $this->view->set('articles', $articles);
        $this->view->set('state', 'many');
        $this->view->set('paginate', $paginate);

        echo $this->view->outputCombine();
    }

    public function edit($postId)
    {
        if(!$this->user->hasPermission('editAllArticles') && !$this->user->hasPermission('editOwnArticle'))
        {
            Route::redirect('error403');
            return true;
        }

        $this->setView('manage');

        if(isset($_POST['saveArticle']))
        {
            $id = (int)($_POST['articleId']);
            $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $intro = htmlspecialchars($_POST['intro'], ENT_QUOTES);
            $text = htmlspecialchars($_POST['text'], ENT_QUOTES);
            $published = (int)($_POST['published']);
            $authorId = $this->user->getId();
            $createdon = time();

            if(!empty($id))
            {
                $article = $this->model;
                $article->getArticleById($id);
                $article->setTitle($title);
                $article->setIntro($intro);
                $article->setText($text);
                $article->setStatus($published);
                if($article->save())
                    $this->view->set('message', 'Article is successfully saved.');
                else
                    $this->view->set('errorMessage', 'Not able to save the article.');
            }
        }

        if(empty($postId))
        {
            Route::redirect('blog/manage');
        }
        else
        {
            $article = $this->model;
            $article->getArticleById((int)$postId);

            if(!$this->user->hasPermission('editAllArticles') && $article->getAuthorId() != $this->user->getId())
            {
                Route::redirect('error403');
                return false;
            }

            $this->view->set(array(
                'state' => 'one',
                'pageHeader' => 'Edit article',
                'isNewRecord' => false,
                'articleId' => $article->getId(),
                'title' => $article->getTitle(),
                'intro' => $article->getIntro(),
                'text' => $article->getText(),
                'published' => $article->getStatus(),
                'author' => $article->getAuthorName(),
                'createdon' => strftime('%d.%m.%Y %H:%M', $article->getCreateDate()),
            ));
        }
        $this->view->set('formAction', 'edit/'.$postId);
        echo $this->view->outputCombine();
    }

    public function create()
    {
        if(!$this->user->hasPermission('createArticle'))
        {
            Route::redirect('error403');
            return true;
        }

        $this->setView('manage');

        if(isset($_POST['saveArticle']))
        {
            $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
            $intro = htmlspecialchars($_POST['intro'], ENT_QUOTES);
            $text = htmlspecialchars($_POST['text'], ENT_QUOTES);
            $published = (int)($_POST['published']);
            $authorId = $this->user->getId();
            $createdon = time();

            $article = $this->model;
            $article->setTitle($title);
            $article->setIntro($intro);
            $article->setText($text);
            $article->setStatus($published);
            $article->setAuthorId($authorId);
            $article->setCreateDate($createdon);
            if($article->save())
                $this->view->set('message', 'Article is successfully saved.');
            else
                $this->view->set('errorMessage', 'Not able to save the article.');
        }

        $this->view->set(array(
            'pageHeader' => 'Create new article',
            'isNewRecord' => true,
            'published' => 1,
            'state' => 'one'
        ));
        $this->view->set('formAction', 'create');
        echo $this->view->outputCombine();
    }

    public function delete($postId)
    {
        if(!$this->user->hasPermission('deleteAllArticles') && !$this->user->hasPermission('deleteOwnArticle'))
        {
            Route::redirect('error403');
            return true;
        }


        if(!empty($postId))
        {
            $article = $this->model;
            $article->getArticleById((int)$postId);

            // If trying delete the post of other author without necessary rights
            if(!$this->user->hasPermission('deleteAllArticles') && $article->getAuthorId() != $this->user->getId())
            {
                Route::redirect('error403');
                return false;
            }

            $article->deleteSelf();
        }

        Route::redirect('blog/manage');
    }
}