<?php

    use Engine\Html\Html;

?>

<h1>Articles</h1>

<?php
    if(!empty($articles) && is_array($articles)){
        echo '<ul>';
        
        foreach ($articles as $article) {
        
            $link = Html::link($article->title, "/{$article->section}/{$article->category}/{$article->id}");

            echo Html::tag('li', [], $link);
        }
        
        echo '</ul>';
    }else{
        echo 'Sorry,  no articles found';
    }
    
?>
