<?php

class MoviesController extends AppController
{
    public $uses = array('Movie', 'User', 'UserMovie');
        
    public $components = array('RequestHandler', 'GoogleAuth');
    
    public function index()
    {
        if($this->_is_auth()){
            $this->redirect(array('controller' => 'movies', 'action' => 'my-movies'));
        }
    }
    
    public function my_movies()
    {
        //Gets an user if we have one or attempt to authorize an user with Google.
        $user = $this->_is_auth();
        if(!$user){ 
            $this->redirect($this->GoogleAuth->auth()); 
        }
        
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
        
        $user_id = $user['User']['id'];

        //Handle form submission to add a movie to the user
        if($this->request->is('post') || $this->request->is('put')){ 
            $request = $this->request->data; 
            $movie_id = $request['Movie']['id'];
            $data['UserMovie'] = compact('movie_id', 'user_id');
            $this->UserMovie->save($data);
        }
        
        $movie_ids = $this->UserMovie->userMovieIds($user_id); 
        
        if(!empty($movie_ids)){ 
            //Get the data for the user's movies
            $movies_arr = $this->Movie->find('all', array('conditions' => array(
                                                        'id' => $movie_ids
                                                )));
            $movies = array_shift($movies_arr);
            $this->set(compact('movies'));
        }
    }
    
    public function oauth2callback()
    {   
        $request = $this->request->query; 
        $email = $this->GoogleAuth->callback($request); 
        $this->Session->write('google_email', $email);
        if($email){
            $this->redirect(array('controller' => 'movies', 'action' => 'my-movies'));
        }
        $this->redirect(array('controller' => 'movies', 'action' => 'index'));
    } 
    
    /**
     * Check if we have a google email in session.
     * Get the user based on email or create a new user with email.
     */
    protected function _is_auth()
    {
        $email = $this->Session->read('google_email'); 
        if($email){
            $user = $this->User->find('first', array('conditions' => array('User.email' => $email)));
            if(!$user){
                $user = $this->User->save(compact('email'));
            }
            return $user;
        }
        return false;
    }
    
    /**
     * Destroy session.
     */
    public function logout()
    {
        $this->Session->delete('google_email');
        $this->redirect(array('controller' => 'movies', 'action' => 'index'));
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
    
            
    /**
     * Search for a movie with $_GET request
     */
    public function search($search = null)
    {   
        if(!$search){ 
            $this->redirect(array('controller' => 'movies', 'action' => 'my-movies'));
        }
        
        $movies = $this->_get_searched_movies($search);
        
        $this->set(compact('movies')); 
    }
}