<?php

class MoviesController extends AppController
{
    public $uses = array();
    private $api_key;
    
    public function beforeFilter()
    {   
        parent::beforeFilter();
        App::uses('HttpSocket', 'Network/Http');
        
        App::uses('PhpReader', 'Configure');
        Configure::config('default', new PhpReader());
        Configure::load('api', 'default');
    }
    
    /**
     * Get the api key from the config file and set member var
     */
    public function get_api_key()
    {   
        return $this->api_key = Configure::read('api_key');
    }
    
    public function index($search = null)
    {

    }
    
    public function titles($search = null)
    {
        if(!$search){ 
            exit;
        }
        
        $movies_arr = json_decode($this->_get_movies($search), true);
        $movies = $movies_arr['movies']; 
        
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
        
        $movies_arr = json_decode($this->_get_movies($search), true);
        $movies = $movies_arr['movies']; 
        
        $this->set(compact('movies')); 
    }
    
    protected function _get_movies($search = null)
    {
        $query = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=';
        $query .= $this->get_api_key() . '&q=' . urlencode($search);
       
        $HttpSocket = new HttpSocket();
        
        return $HttpSocket->get($query);
    }
}