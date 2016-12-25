<?php

namespace App\Models;

use Engine\Models\Model;

/**
 * 
 * many to many relation hack
 * 
 * pivot model 
 * implements section to category relation
 * 
 * @property integer $section_id
 * @property integer $category_id
 * @property integer $ord
 */
class SectionCategory extends Model{
    
    protected $table = 'section_category';
    
    public function getCategories($section_id){
        $query = 'SELECT * FROM categories c '
                . 'JOIN section_category s_c on s_c.category_id=c.id '
                . 'WHERE s_c.section_id = :section_id';
        
        $params = [
            ':section_id'=>$section_id
        ];
        
        $data = $this->dbConnection->selectRaw($query, $params);
        
        $categories = [];
        foreach ($data as $category_data) {
            $category = new Category;
            $category->hydrate($category_data);
            $categories[$category->alias] = $category;
        }
        
        return $categories;
    }
}
