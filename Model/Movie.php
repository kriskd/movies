<?php
class Movie extends AppModel
{
    public $useDbConfig = 'movie';
        
    //public $actsAs = array('Containable');
    
    public $hasAndBelongsToMany = array(
        'User' => array(
            'className' => 'User',
            'joinTable' => 'users_movies',
            'foreignKey' => 'movie_id',
            'associationForeignKey' => 'user_id'
        )  
    );
}