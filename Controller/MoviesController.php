<?php

class MoviesController extends AppController
{
    public $uses = array('Movie', 'User', 'UserMovie');
        
    public $components = array('RequestHandler');
    
    public function beforeFilter()
    {   
        parent::beforeFilter();
    }
    
    public function index()
    {
        //Handle ajax request for autocomplete
        if($this->request->is('ajax')){
            $query = $this->request->query;
            $search = $query['term'];
            if(!$search){ 
                throw new NotFoundException('Search term required');
            }
            
            $movies = $this->_get_searched_movies($search);
            
            $titles = array_map(function($movie){
                    return array('id' => $movie['id'], 'value' => $movie['title']);
                }, $movies);

            $this->set(compact('titles'));
        }
        
        //Hard code an user_id for now
        $user_id = 1;
        
        $users_movies = $this->UserMovie->find('all', array('conditions' => array('user_id' => $user_id),
                                                      'fields' => array('movie_id'),
                                                      'recursive' => -1));
        //Make an array of just the movie ids
        $movie_ids = array_map(function($item){
            return $item['UserMovie']['movie_id'];
        }, $users_movies);

        if($this->request->is('post') || $this->request->is('put')){ 
            $request = $this->request->data;
            $movie_id = $request['Movie']['id'];
            if(!in_array($movie_id, $movie_ids)){
                $data['UserMovie'] = compact('movie_id', 'user_id');
                $this->UserMovie->save($data);
            }
        }

        
        //Get the data for the user's movies
        $movies_arr = $this->Movie->find('all', array('conditions' => array(
                                                    'id' => $movie_ids
                                            )));
        $movies = array_shift($movies_arr);
        $this->set(compact('movies'));
    }
    
    /*
     * Search for a movie with $_GET request
     */
    public function search($search = null)
    {   
        if(!$search){ 
            $this->redirect('/movies');
        }
        
        $movies = $this->_get_searched_movies($search);
        
        $this->set(compact('movies')); 
    }
    
    /**
     * Returns an array of movies based on search term
     * @param $search Search term for movie
     */
    protected function _get_searched_movies($search = null)
    {
        //Get movies based on search term
        $search = urlencode($search);
        $movies_arr = $this->Movie->find('search', array('conditions' => array('q' => $search)));
        $movies_arr = array_shift($movies_arr); 
        $movies = $movies_arr['movies'];
        return $movies;
    }
}