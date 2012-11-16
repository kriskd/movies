<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('PhpReader', 'Configure');

class GoogleAuthComponent extends Component
{
    protected $redirect_uri;
    protected $oauth_client_id;
    protected $oauth_client_secret; 
    
    public function __construct(ComponentCollection $collection, $settings = array())
    {
        $this->HttpSocket = new HttpSocket();
        Configure::config('default', new PhpReader());
        Configure::load('google', 'default');
        $this->redirect_uri = Configure::read('redirect_uri');
        $this->oauth_client_id = Configure::read('oauth_client_id');
        $this->oauth_client_secret = Configure::read('oauth_client_secret'); 
    }
    
    public function auth()
    {   
        $url = "https://accounts.google.com/o/oauth2/auth";
 
        $params = array(
            "response_type" => "code",
            "client_id" => $this->oauth_client_id,
            "redirect_uri" => $this->redirect_uri,
            'scope' => 'https://www.googleapis.com/auth/userinfo.email',
            );
         
        $request_to = $url . '?' . http_build_query($params);
        return $request_to;
    }
    
    public function callback($request)
    {
        $code = $request['code'];
        $url = 'https://accounts.google.com/o/oauth2/token';
        $params = array(
            "code" => $code,
            "client_id" => $this->oauth_client_id,
            "client_secret" => $this->oauth_client_secret,
            "redirect_uri" => $this->redirect_uri,
            "grant_type" => "authorization_code"
        );
       
        $response = $this->HttpSocket->post($url, $params);
        $arr = json_decode($response, true);
        
        if(isset($arr['access_token'])){
            $access_token = $arr['access_token'];
            $response = $this->HttpSocket->get('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $access_token);
            $arr = json_decode($response, true);
            
            return $arr['email'];
        }
    }
}