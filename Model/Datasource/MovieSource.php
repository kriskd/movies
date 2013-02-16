<?php
App::uses('HttpSocket', 'Network/Http');

class MovieSource extends DataSource
{
    public $config = array('apiKey' => '');
    
    protected $_schema = array(
            'id' => array(
                'type' => 'integer',
                'null' => false,
                'key' => 'primary',
                'length' => 11),
            'title' => array(
                'type' => 'string',
                'null' => false,
                'length' => 255),
            'release_dates' => array(
                'theater' => array(
                    'type' => 'date',
                    'null' => true
                ),
                'dvd' => array(
                    'type' => 'date',
                    'null' => true
                )
            )
        );
    
    public function __construct($config)
    {
        parent::__construct($config);
        $this->Http = new HttpSocket();
    }
    
    public function listSources($data = null)
    {
        return null;
    }
    
    public function describe($model)
    {
        return $this->_schema;
    }
    
    public function read(Model $model, $queryData = array(), $recursive = null)
    {   
        $queryData['conditions']['apikey'] = $this->config['apiKey'];
        
        //Movie text search
        if(strcasecmp($model->findQueryType, 'search')==0){
            $json = $this->Http->get('http://api.rottentomatoes.com/api/public/v1.0/movies.json', $queryData['conditions']);
            $results = json_decode($json, true); 
        }
        //Get the user's movies
        else{ 
            $ids = $queryData['conditions']['id'];
            unset($queryData['conditions']['id']);
            foreach($ids as $key => $id){ 
                $json = $this->Http->get('http://api.rottentomatoes.com/api/public/v1.0/movies/' . $id . '.json', $queryData['conditions']);
                $results[$key] = json_decode($json, true); 
            }
            $current = current($results);
            if(isset($current['error'])){ 
                return array($model->alias => $current['error']);
            }
            //This needs to be an option in the find method rather than hard coded here
            uasort($results, function($a, $b){return ($a['title'] < $b['title']) ? -1 : 1;});
        }
        
        if(!isset($results)){
            $error = json_last_error();
            throw new CakeException($error);
        }
        return array($model->alias => $results);
    }
}