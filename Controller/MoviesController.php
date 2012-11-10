<?php

class MoviesController extends AppController
{
    public $uses = array();
    private $api_key;
    
    public function beforeFilter()
    {   
        parent::beforeFilter();
    }
    
    public function index($search = null)
    {

    }
    
    public function titles($search = null)
    {
        if(!$search){ 
            exit;
        }
        
        $movies = $this->_get_movies($search);
        
        $titles = array_map(function($movie){
                return array('id' => $movie['id'], 'value' => $movie['title']);
            }, $movies);

        echo json_encode($titles);
        exit;
    }
    
    /**
     * Returns an array of movies based on search term
     * @param $search Search term for movie
     */
    public function search($search = null)
    {   
        if(!$search){ 
            $this->redirect('/movies');
        }
        
        $movies = $this->_get_movies($search);
        
        $this->set(compact('movies')); 
    }
    
    protected function _get_movies($search = null)
    {
        $search = urlencode($search);
        $movies_arr = $this->Movie->find('all', array('conditions' => array('q' => $search)));
        $movies_arr = array_shift($movies_arr);
        return $movies_arr['movies'];
    }
}