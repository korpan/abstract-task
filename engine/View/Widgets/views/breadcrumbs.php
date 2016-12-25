<?php

use Engine\Html\Html;


echo '<ul class="breadcrumbs clearfix">';

foreach ($crumbs as $crumb) {
    
    $link =  Html::link($crumb['text'], '/'.$crumb['url']);
    
    echo Html::tag('li', [], $link);
    
    echo Html::tag('li', [], '/');
    
}

echo '</ul>';
?>