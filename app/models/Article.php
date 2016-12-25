<?php

namespace App\Models;

use Engine\Models\Model;

/**
 * @property integer $id
 * @property string $title
 * @property string $body
 * @property integer $category_id
 * 
 */
class Article extends Model{
    
    protected $table = 'articles';
    
    
    public function handleParsedData($data, $additional_data){
        if(isset($additional_data['section']) && !empty($additional_data['section'])){
            //It seems to be that only parent categories have type, so to find top level category use condition "type is not null"
            $section = Category::model()->findByAttributes(['alias'=>$additional_data['section'], 'type'=>'IS NOT NULL']);
            if(empty($section)){
               return false; 
            }
            $categories = $section->children;
        }
        
        foreach ($data as $article_data) {
            if(isset($categories[$article_data['category']])){
                $article = new Article;
                $article->hydrate($article_data);

                $article->category_id = $categories[$article_data['category']]->id;
                
                $article->save(false);
            }
        }
    }
    
    
    public function getArticles($section, $category=null, $id=null){
        
        $query = 'SELECT art.*, cat.alias as category, sect.alias as section FROM articles art '
                . 'JOIN categories cat on art.category_id=cat.id '
                . 'JOIN section_category s_c on s_c.category_id=cat.id '
                . 'JOIN categories sect on s_c.section_id=sect.id '
                . 'WHERE sect.alias = :section ';
        
        $params = [
            ':section'=>$section,
        ];
        
        if($category!=null){
            $query.= 'AND cat.alias = :category ';
            $params[':category'] = $category;
        }
        
        if($id!=null){
            $query.= 'AND art.id = :id ';
            $params[':id'] = $id;
        }
        
        $query.= 'ORDER BY art.created_at DESC';
        
        $data = $this->dbConnection->selectRaw($query, $params);
        
        $articles = [];
        foreach ($data as $article_data) {
            $article = new Category;
            $article->hydrate($article_data);
            $articles[] = $article;
        }
        
        return $articles;
    }
}
