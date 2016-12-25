<?php

use Engine\Html\Html;
use Engine\View\Widgets\SidebarNavigationWidget;

/* @var $this SidebarNavigationWidget */


echo '<ul class="navigation">';

foreach ($nav_tree as $node) {
    
    $title = $node['title'];
    
    $link = Html::link(ucfirst($title), "/{$title}");
    
    echo Html::tag('li', [], $link);
    
    if($node['children']){
        echo '<li>';
            echo '<ul>';
            foreach ($node['children'] as $child) {

                $child_title = $child['title'];

                $link = Html::link(ucfirst($child_title), "/{$title}/{$child_title}");

                echo Html::tag('li', [], $link);
            } 
            echo '</ul>';
        echo '</li>';
    }
}
echo '</ul>';