<?php
class User extends AppModel
{
    public $name = 'User';
    
    public $hasAndBelongsToMany = array(
        'Movie' => array(
            'className' => 'Movie',
            'joinTable' => 'users_movies',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'movie_id'
        )
    );
}