<?php
class Movie extends AppModel
{
    public $useDbConfig = 'movie';
        
    public $findMethods = array('search' => true);
    
    /*
     * Define a custom find to differentiate between searching for a
     * movie title and getting a movie by id.
     * @param $state state of the query execution
     */
    protected function _findSearch($state, $query, $results = array())
    {
        if($state == 'before'){
            return $query;
        }
        return $results;
    }
    
    public $hasAndBelongsToMany = array(
        'User' => array(
            'className' => 'User',
            'joinTable' => 'users_movies',
            'foreignKey' => 'movie_id',
            'associationForeignKey' => 'user_id'
        )  
    );
}