<?php
namespace lib;
class Request extends \core\Lib{

    var $request;
    var $session;
    var $get;
    var $post;
    var $method;

    function __construct()
    {
        $this->request=$this->initialize($_REQUEST);
        $this->session=$this->initialize($_SESSION);
        $this->get=$this->initialize($_GET);
        $this->post=$this->initialize($_POST);
        $this->method=$_SERVER['REQUEST_METHOD'];
    }

    private function initialize(&$var){
        $temp=$var;
        unset($var);
        return $temp;
    }


    public function getRawData(){
        return file_get_contents("php://input");
    }

    public function getData($assoc=false){
        $data=$this->getRawData();
        return $this->http->parse($this->getHeaders(),$data,$assoc);
    }

    public function getHeaders(){
        return array(array_change_key_case(getallheaders(),CASE_LOWER));
    }

    public function getHeaderByName($name){
        $header=$this->getHeaders();

        if(!is_array($header)) {
            $header = $this->http->headersToArray($header);
        }

        //if the content type is set
        if(isset($header[0][$name])){
            return $header[0][$name];
        }
        return "";
    }

}
?>