<?php
class UserMovie extends AppModel
{
    public $name = 'UserMovie';
    
    public $useTable = 'users_movies';
    
    public $actsAs = array('Containable');
    
    public $belongsTo = array(
            'Movie' => array(
                'className' => 'Movie',
                'foreignKey' => 'movie_id'
            ),
            'User' => array(
                'className' => 'User',
                'foreignKey' => 'user_id'
            )
        );
}