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
        $this->set('title_for_layout', 'Movies : Authenticate');
    }
    
    public function my_movies()
    {
        //Gets an user if we have one or attempt to authorize an user with Google.
        $user = $this->_is_auth(); 
        if(empty($user)){ 
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
            //Unset this in order to prevent the movie id from appearing in the hidden field on the form
            unset($this->request->data);
            $movie_id = $request['Movie']['id'];
            $data['UserMovie'] = compact('movie_id', 'user_id');
            $this->UserMovie->save($data);
        }
        
        $user_movies = $this->UserMovie->find('list', array('conditions' =>
                                                           array('user_id' => $user_id),
                                                           'recursive' => -1,
                                                           'fields' => array('id', 'movie_id'))
                                                    );
        
        if(!empty($user_movies)){ 
            //Get the data for the user's movies
            $movies_arr = $this->Movie->find('all', array('conditions' => array(
                                                        'id' => $user_movies
                                                )));
            $movies = array_shift($movies_arr);
            $this->set(compact('movies'));
        }
        
        $this->set('title_for_layout', 'Movies : List');
    }
    
    public function delete()
    {
        $referer = $this->referer(null, true);
        $route = Router::url(array('controller' => 'movies', 'action' => 'my-movies')); 
        if($this->request->is('ajax') && strcasecmp($referer, $route)==0){
            $user = $this->Session->read('user'); 
            $user_id = $user['User']['id'];
            $data = $this->request->data;
            $id = current($data['id']);
            $this->UserMovie->deleteAll(array('UserMovie.id' => $id, 'UserMovie.user_id' => $user_id), false);
            $this->autoRender = false;
        }
    }
    
    public function oauth2callback()
    {   
        $request = $this->request->query; 
        $email = $this->GoogleAuth->callback($request);
        $user = $this->User->find('first', array('conditions' => compact('email')));
        $this->Session->write('user', $user);
        if(!empty($user)){
            $this->redirect(array('controller' => 'movies', 'action' => 'my-movies'));
        }
        $this->redirect(array('controller' => 'movies', 'action' => 'index'));
    } 
    
    /**
     * Check if we have a user in session and return.
     */
    protected function _is_auth()
    {
        $user = $this->Session->read('user');
        if(!empty($user)){
            return $user;
        }

        return false;
    }
    
    /**
     * Destroy session.
     */
    public function logout()
    {
        $this->Session->delete('user');
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
     * Disable this for now
     */
    /*public function search($search = null)
    {   
        if(!$search){ 
            $this->redirect(array('controller' => 'movies', 'action' => 'my-movies'));
        }
        
        $movies = $this->_get_searched_movies($search);
        
        $this->set(compact('movies')); 
    }*/
}