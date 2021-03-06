<?php
namespace core;
class Base
{


    var $__module;

    public function __get($name)
    {
        //dynamically load libraries
        return Loader::getSingleton($name, "lib");
    }

    public function goMyModule()
    {
        if ($this->__module) {
            $this->goModule($this->__module);
        } else {
            $cls = get_class($this);
            if (substr($cls, 0, 7) == "modules") {
                $exp = explode("\\", $cls);
                $this->goModule($exp[1]);
            } else {
                $this->goModule("");
            }
        }

    }

    public function goModule($name)
    {
        Loader::goModule($name);
    }

    public function goBackModule()
    {
        Loader::goBackModule();
    }




    public function __onClassPostConstruct($data)
    {
        if($this->event) {
            $this->event->raise("classPostConstruct", $data);
        }
    }

    public function __onMethodPreCall($data){
        if($this->event) {
            $this->event->raise("methodPreCall", $data);
        }
    }

    public function __onMethodPostCall($data){
        if($this->event) {
            $this->event->raise("methodPostCall", $data);
        }
    }

    public function __onFieldPreGet($data){
        if($this->event) {
            $this->event->raise("fieldPreGet", $data);
        }
    }

    public function __onFieldPostGet($data){
        if($this->event) {
            $this->event->raise("fieldPostGet", $data);
        }
    }

    public function __onFieldPreSet($data){
        if($this->event) {
            $this->event->raise("fieldPreSet", $data);
        }
    }

    public function __onFieldPostSet($data){
        if($this->event) {
            $this->event->raise("fieldPostSet", $data);
        }
    }


}

?>