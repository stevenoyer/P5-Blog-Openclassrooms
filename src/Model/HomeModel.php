<?php 

namespace So\Blog\Model;

use So\Blog\Class\Model;

class HomeModel extends Model
{
    protected $table = '';

    public function read()
    {
        return ['items' =>
            [
                ['title' => 'Un article', 'content' => 'Hello World', 'author' => 'John Doe', 'created_at' => '10-10-2022 10:00:00'],
                ['title' => 'Un titre', 'content' => 'Un contenu', 'author' => 'John Doe', 'created_at' => '10-11-2022 11:00:00'],
                ['title' => 'Lorem ipsum', 'content' => 'Libero repudiandae ullam sunt voluptates aliquam rerum impedit rem facere atque!', 'author' => 'John Doe', 'created_at' => '10-01-2023 12:00:00']
            ]
        ];
    }
}