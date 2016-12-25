<?php


namespace App\Components\Widgets;

use App\Models\Category;
use Engine\View\Widgets\SidebarNavigationWidget;


class CategoriesNavigationWidget extends SidebarNavigationWidget{
    
    protected $categoriesTree = [];
    
    public function __construct($config=[]) {
        
        $config['nav_tree'] = $this->formNavTree(Category::model()->getTree());
        
        parent::__construct($config);
    }
    
    protected function formNavTree($categories){
        
        $nav_tree = [];
        foreach ($categories as $category) {
            $nav_node = [
                'title'=>$category->alias,
            ];
                    
            if(!empty($category->children)){
                foreach ($category->children as $child) {
                    $nav_node['children'][] = ['title'=>$child->alias]; 
                }
            }
            
            $nav_tree[] = $nav_node;
        }
        
        return $nav_tree;
    }
    
}
