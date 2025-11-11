<?php
namespace App\Bd;
use \PDO;
use \PDOException;

class Usuario extends Conexion{
    private int $id;
    private string $email;
    private string $password; 

    private static function executeQuery(string $q, array $param=[]): \PDOStatement{
        $stmt=self::getConexion()->prepare($q);
        try{
            $stmt->execute($param);
            return $stmt;
        }catch(PDOException $ex){
            throw new PDOException("Error en la consulta: ".$ex->getMessage());
        }
    }
    public function create(){
        $q="insert into usuarios(email, password) values(:e, :p)";
        self::executeQuery($q, [
            ':e'=>$this->email,
            ':p'=>$this->password
        ]);
    }
    public static function delete(?int $id=null){
        $q=($id==null) ? "delete from usuarios" : "delete from usuarios where id=:i";
        $param=($id==null) ? [] :[':i'=>$id];
        self::executeQuery($q, $param);
    } 
    public static function getIdsUsuarios():array{
        $q="select id from usuarios";
        $stmt=self::executeQuery($q);
        $datos=$stmt->fetchAll(PDO::FETCH_OBJ);
        $users=[];
        foreach($datos as $item){
            $users[]=$item->id;
        }
        return $users;
    }
    public static function loginValido(string $email, string $password): bool{
        $q="select password from usuarios where email=:e";
        $stmt=self::executeQuery($q,[':e'=>$email]);
        $datos=$stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($datos)){
            $pass=$datos[0]['password'];
            //$pass=$datos[0]->password; si hubiesemos hecho el FETCH_OBJ
            return password_verify($password, $pass);
        }
        return false;


    }


    public static function crearUsuarios(int $cant){
        $faker = \Faker\Factory::create('es_ES');
        for($i=0; $i<$cant; $i++){
            $email=$faker->unique()->email();
            $password="secret0";
            (new Usuario)
            ->setEmail($email)
            ->setPassword($password)
            ->create();
        }
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of password
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT) ;

        return $this;
    }
}