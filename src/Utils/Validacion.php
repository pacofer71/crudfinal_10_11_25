<?php

namespace App\Utils;

use App\Bd\Categoria;
use App\Bd\Usuario;

abstract class Validacion
{
    public static function sanearCadenas(string $cad): string
    {
        return htmlspecialchars(trim($cad));
    }
    public static function longitudCampoValida(string $nomCampo, string $valorCampo, int $min, int $max): bool
    {
        if (strlen($valorCampo) < $min || strlen($valorCampo) > $max) {
            $_SESSION["err_$nomCampo"] = "*** Error, la longitud de $nomCampo debe estar entre $min y $max";
            return false;
        }
        return true;
    }
    public static function categoriaValida(int $idCat): bool
    {
        $categoriasId = Categoria::getIdsCategorias();
        if (in_array($idCat, $categoriasId)) return true;
        $_SESSION['err_category_id'] = "*** Error, categoria no seleccioada o inválida";
        return false;
    }
    public static function imagenValida(string $tipo, int $size)
    {
        $mime_tipos_validos = [
            'image/jpeg',  // JPG / JPEG
            'image/png',   // PNG
            'image/gif',   // GIF
            'image/webp',  // WEBP
            'image/bmp',   // BMP
            'image/tiff',  // TIFF
            'image/svg+xml', // SVG
            'image/x-icon', // ICO
            'image/heic',   // HEIC (formato de Apple)
            'image/heif'    // HEIF (High Efficiency Image File)
        ];
        if (!in_array($tipo, $mime_tipos_validos)) {
            $_SESSION['err_imagen'] = "*** Error, se esperaba un archivo de imagen";
            return false;
        }
        if ($size > 2000000) {
            $_SESSION['err_imagen'] = "*** Error, el tamaño de la imagen NO no puede exceder 2MB";
            return false;
        }
        return true;
    }
    public static function estadoValido(string $estado): bool{
        $estados=['Publicado', 'Borrador'];
        if(!in_array($estado, $estados)){
            $_SESSION['err_estado']="*** Error, estado inválido o no seleccionado";
            return false;
        }
        return true;
    }


    public static function emailValido(string $email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        $_SESSION['err_email'] = "*** Error, se esperaba un email válido.";
        return false;
    }

    public static function loginValido(string $email, string $pass): bool
    {
        if (!Usuario::loginValido($email, $pass)) {
            $_SESSION['err_login'] = "Error, email o password inválidos.";
            return false;
        }
        return true;
    }

    public static function pintarError(string $errorName): void
    {
        if (isset($_SESSION[$errorName])) {
            echo "<p class='italic text-red-500 tex-sm mt-1'>{$_SESSION[$errorName]}</p>";
            unset($_SESSION[$errorName]);
        }
    }
}
