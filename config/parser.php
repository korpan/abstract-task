<?php


return [
    'sequence'=>[
        [
            'file'=>__DIR__.'/../storage/files/categories.json',
            'format'=>'json',
            'model'=>'\App\Models\Category'
        ],
        [
            'file'=>__DIR__.'/../storage/files/news.json',
            'format'=>'json',
            'model'=>'\App\Models\Article',
            'additional_data'=>[
                //specify section for articles, because data in file contains only category and this category could be in different sections
                'section'=>'news'
            ],
        ],
        [
            'file'=>__DIR__.'/../storage/files/blogs.json',
            'format'=>'json',
            'model'=>'\App\Models\Article',
            'additional_data'=>[
                'section'=>'blogs'
            ],
        ],
        [
            'file'=>__DIR__.'/../storage/files/forums.json',
            'format'=>'json',
            'model'=>'\App\Models\Article',
            'additional_data'=>[
                'section'=>'forums'
            ],
        ],
    ],
    
];