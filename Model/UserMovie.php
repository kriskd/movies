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
    
    /**
     * Make sure user doesn't already have that movie
     */
    public function beforeSave($options = array())
    {
        $data = $this->data;
        $user_id = $data['UserMovie']['user_id'];
        $movie_id = $data['UserMovie']['movie_id'];
        
        $user_movie_ids = $this->userMovieIds($user_id);
        
        if(in_array($movie_id, $user_movie_ids)){
            return false;
        }
        
        return true;
    }
    
    public function userMovieIds($user_id)
    {
        return $this->find('list', array('conditions' => array('user_id' => $user_id),
                                              'fields' => array('movie_id'),
                                              'recursive' => -1));
    }
}