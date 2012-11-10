<?php
App::uses('HttpSocket', 'Network/Http');

class MovieSource extends DataSource
{
    public $config = array('apiKey' => '');
    
    public function __construct($config)
    {
        parent::__construct($config);
        $this->Http = new HttpSocket();
    }
    
    public function listSources($data = null)
    {
        return null;
    }
    
    public function read(Model $model, $queryData = array(), $recursive = null)
    {
        //API calls
        //http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=[your_api_key]
        //http://api.rottentomatoes.com/api/public/v1.0/movies/770672122.json?apikey=[your_api_key]

        $queryData['conditions']['apikey'] = $this->config['apiKey']; 
        $json = $this->Http->get('http://api.rottentomatoes.com/api/public/v1.0/movies.json', $queryData['conditions']);
        
        $results = json_decode($json, true); 
        if(!$results){
            $error = json_last_error();
            throw new CakeException($error);
        }
        return array($model->alias => $results);
    }
}