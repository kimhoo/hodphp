<?php
namespace lib;
session_start();
class Session extends \core\Lib{
    function __get($name)
    {
       return $_SESSION[$name];
    }

    function __set($name, $value)
    {
      $_SESSION[$name]=$value;
    }
}
?>