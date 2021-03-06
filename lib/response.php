<?php
    namespace lib;
    use core\Loader;

    class Response extends \core\Lib{

        var $partialMode=false;

       function write($string,$options=Array()){
            echo $string;
        }

        function renderAction($parameters=""){


            if(func_num_args()>1){
                $parameters=func_get_args();
            }

            Loader::loadAction($parameters);
        }


        function renderView($data=Array(),$path=""){

            if($this->partialMode){
                return $this->renderPartial($data,$path);
            }
            if(is_object($data)){
                $data=$data->toArray();
            }
            if(!is_array($data)){
                $path=$data;
                $data=Array();
            }

            if(!$path){
                $path=\core\Loader::$controller."/".(\core\Loader::$action);
            }

            $content=$this->template->parseFile($path,$data);
            $this->write($this->template->parseFile("main",Array("content"=>$content)));
        }


        function renderContent($content){
            $this->write($this->template->parseFile("main",Array("content"=>$content)));
        }

         function renderPartial($data=Array(),$path=""){
             if(is_object($data)){
                 $data=$data->toArray();
             }

            if(!is_array($data)){
                $path=$data;
            }

            if(!$path){
                $path=\core\Loader::$controller."/".\core\Loader::$action;
            }

            $this->write($this->template->parseFile($path,$data));
        }

        function renderFile($data,$contentType){
            $this->contentType($contentType);
            $this->write($data);
            die();
        }


        function renderJson($data){
            $this->contentType("application/json");
            $this->write($this->serialization->serialize("json",$data));
        }


        function header($key,$value){
            header($key.": " .$value);
        }

        function contentType($type){
            $this->header("content-type",$type);
        }

        function redirect(){
            $this->header("location",$this->route->createRoute(func_get_args()));

            die();
        }

        function fakeResponse($data,$function){
            ob_start();
            $function($data);
            $content=ob_get_contents();
            ob_end_clean();

            return $content;
        }

        function setPartialMode($value){
            $this->partialMode=$value;
        }

    }
?>