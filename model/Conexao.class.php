<?php


class Conexao 
{
    public static $instance;

    public static function get_instance()
    {
        if ( !isset(self::$instance) ) {
            self::$instance = new PDO("mysql:host=127.0.0.1;dbname=bd_caixa_eletronico;", "landre", "acad", 
                                            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));                                        
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
       

        return self::$instance;
    }
}