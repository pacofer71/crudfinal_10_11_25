<?php
namespace App\Bd;
use \PDO;
use \PDOException;
class Conexion{
    private static ?PDO $conexion=null;

    protected static function getConexion(): PDO{
        if(self::$conexion==null){
            self::setConexion();
        }
        return self::$conexion;
    }
    private static function setConexion(){
        $dotenv = \Dotenv\Dotenv::createImmutable(self::getDirRoot());
        $dotenv->load();
        
        $usuario=$_ENV['USUARIO'];
        $db=$_ENV['DATABASE'];
        $host=$_ENV['HOST'];
        $port=$_ENV['PUERTO'];
        $pass=$_ENV['PASSWORD'];

        $dsn="mysql:host=$host;dbname=$db;charset=utf8mb4";
        $options=[
            PDO::ATTR_PERSISTENT=>true,
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES=>false
        ];
        try{
            self::$conexion=new PDO($dsn, $usuario, $pass, $options);
        }catch(PDOException $ex){
            throw new PDOException("Error en la comexion: ".$ex->getMessage());
        }


    }
    private static function getDirRoot(){
        return dirname(__DIR__, 2);
    }
}
