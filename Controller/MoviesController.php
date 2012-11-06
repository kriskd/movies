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
    
    /**
     * Returns an array of movies based on search term
     * @param $search Search term for movie
     */
    public function search($search = null)
    {   
        if(!$search){ 
            $this->redirect('/');
        }
        
        $query = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=';
        $query .= $this->get_api_key() . '&q=' . urlencode($search);
       
        $HttpSocket = new HttpSocket();
        
        $results = $HttpSocket->get($query);
        $movies_arr = json_decode($results, true);
        $movies = $movies_arr['movies']; 
        
        $this->set(compact('movies')); 
    }
}