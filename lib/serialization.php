<?php
namespace lib;
use core\Loader;

//simple wrapper around the serializers
//so serialization can be done by just giving a serializername and the data
class Serialization extends \core\Lib
{
    function __construct(){
        Loader::loadClass("serializer","lib\\serialization");
    }

    function serialize($format,$data){
        if(is_array($data)){
            foreach($data as $key=>$val){
                if(is_object($data[$key]) && method_exists($data[$key],"toArray")){
                    $data[$key]=$data[$key]->toArray();
                }
            }
        }
        else if(is_object($data) && method_exists($data,"toArray")){
            $data=$data->toArray();
        }

        return $this->LoadSerializer($format)->serialize($data);
    }

    function unserialize($format,$data,$assoc=false){
        return $this->LoadSerializer($format)->unserialize($data,$assoc);
    }


    private function LoadSerializer($name){
        return Loader::getSingleton($name,"lib\\serialization\\serializers");
    }
}