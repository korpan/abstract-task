<?php

namespace App\Models;

use Engine\Models\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $type
 * @property integer $parent_id
 * @property integer $ord
 * 
 * 
 */
class Category extends Model{

    protected $table = 'categories';

    protected $_children = null;
    
    public function handleParsedData($data, $parent_id = null){
        foreach ($data as $category_data) {
            
            //check if category exists to not create duplicates
            //if it exists and:
            // - if it's a child category - it will be attached to specified parent
            // - if it's top level category - duplicate will just be ignored
            
            $category = Category::model()->findByAttributes(['alias'=>$category_data['alias']]);
            
            if(empty($category)){
                $category = new Category;
                $category->hydrate($category_data);
                $category->save(false);
            }
            
            if($parent_id!=null){
                $section_category = new SectionCategory;
                $section_category->section_id = $parent_id;
                $section_category->category_id = $category->id;
                $section_category->ord = $category_data['ord'];
                $section_category->save(false);
            }else if(isset($category_data['children']) && !empty($category_data['children'])){
                $this->handleParsedData($category_data['children'], $category->id);
            }
        }
    }
    
    public function getChildren(){
        if($this->_children==null){
            $this->_children = SectionCategory::model()->getCategories($this->id);
        }
        
        return $this->_children;
    }
    
    
    public function getTree(){
        $query = 'SELECT c.*, s_c.section_id AS parent_id, s_c.ord AS ord  FROM categories c '
                . 'LEFT JOIN section_category s_c on s_c.category_id=c.id ORDER BY parent_id, ord ASC';
        
        $data = $this->dbConnection->selectRaw($query);
        
        $categories = [];
        foreach ($data as $category_data) {
            $category = new Category;
            $category->hydrate($category_data);
            
            if(!empty($category->parent_id)){
                $categories[$category->parent_id]->_children[] = $category;
            }else{
                $categories[$category->id] = $category;
            }
        }
        
        return $categories;
    }
}
