<?php
    namespace core;
    class Proxy{
        private $module;
        private $instance;


        function __construct($fullclass,$module)
        {
            $this->module=$module;
            $this->__setModule();
            $this->instance=new $fullclass();
            $this->instance->__module=$this->module;
            $this->__unsetModule();

        }

        function __get($name)
        {
            $this->__setModule();
            $result=   $this->instance->$name;
            $this->__unsetModule();
            return $result;
        }

        function __set($name, $value)
        {

            $this->__setModule();
            $this->instance->$name=$value;
            $this->__unsetModule();
        }

        function __call($name, $arguments)
        {
            $this->__setModule();
            $result=  call_user_func_array(Array($this->instance, $name), $arguments);
            $this->__unsetModule();
            return $result;

        }

        function __debugInfo()
        {
            return $this->toArray();

        }

        public function __setModule(){
            if($this->module) {
                Loader::goModule($this->module);
            }
        }

        public function __unsetModule(){
            if($this->module) {
                Loader::goBackModule();
            }
        }

        public function __isset($name)
        {
            return isset($this->instance->$name);
        }

        public function hasMethod($name){
            return method_exists($this->instance,$name);
        }

        public function toArray(){
            $this->__setModule();
            if(method_exists($this->instance,"_debugInfo")){
                $result=   $this->instance->__debugInfo();
            }
            elseif(method_exists($this->instance,"toArray")){
                $result=$this->instance->toArray();
            }else{
                $result=json_decode(json_encode($this->instance),true);
            }
            $this->__unsetModule();
            if(!is_array($result)){
                $result=array();
            }

            return $result;
        }


    }
?>