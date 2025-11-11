<?php
namespace App\Utils;
use App\Bd\Usuario;

abstract class Validacion{
    public static function sanearCadenas(string $cad): string{
        return htmlspecialchars(trim($cad));
    }
    
    public static function emailValido(string $email): bool{
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        $_SESSION['err_email']="*** Error, se esperaba un email válido.";
        return false;
    }

    public static function loginValido(string $email, string $pass): bool{
        if(!Usuario::loginValido($email, $pass)){
            $_SESSION['err_login']="Error, email o password inválidos.";
            return false;
        }
        return true;
    }

    public static function pintarError(string $errorName):void{
        if(isset($_SESSION[$errorName])){
            echo "<p class='italic text-red-500 tex-sm mt-1'>{$_SESSION[$errorName]}</p>";
            unset($_SESSION[$errorName]);
        }
    }
}