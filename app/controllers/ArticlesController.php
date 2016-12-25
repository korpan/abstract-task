<?php

namespace App\Controllers;

use App\Models\Article;
use Engine\Controllers\Controller;

class ArticlesController extends BaseController{

    protected $layout = 'app.views.layouts.main';
    
    public function resolve($section, $category=null, $id=null){
        if($id!=null){
            $this->getArticle($section, $category, $id);
        }else{
            $this->getArticles($section, $category);
        }
    }
    
    
    protected function getArticles($section, $category){
        $articles = Article::model()->getArticles($section, $category);
        
        $this->render('articles.many', [
            'articles'=>$articles
        ]);
    }
    
    protected function getArticle($section, $category, $id){
        //because we need to make sure that article has correct section and category 
        //we will search not only by id but also by that section and category 
        $article = reset(Article::model()->getArticles($section, $category, $id));
       
        
        $this->render('articles.single', [
            'article'=>$article
        ]);
    }
}
