<!DOCTYPE html>
<html>
    <head>
        <title><?=$this->title?></title>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        
        <link rel="stylesheet" href="/css/main.css" />
        
    </head>
    <body>
        <div class="sidebar">
            <?$this->widget('\App\Components\Widgets\CategoriesNavigationWidget');?>
        </div>
        
        <div class="content">
            <div class="breadcrumbs">
                <?$this->widget('\Engine\View\Widgets\BreadCrumbsWidget');?>
            </div>
            <?=$content;?>
        </div>
        
    </body>
</html>