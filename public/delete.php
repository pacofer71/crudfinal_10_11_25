<?php

use App\Bd\Post;

session_start();
function salir(){
    header("Location:admin.php");
    exit;
}
if(!isset($_SESSION['email'])){
    salir();
}
$email=$_SESSION['email'];

$idPost=filter_input(INPUT_POST, 'idPost', FILTER_VALIDATE_INT);
if(!$idPost) salir();
//necesito comprobar que elpost le pertenece al usuario
//y si es asi recuperar la imagen para decidir si la borramos o no
require __DIR__."/../vendor/autoload.php";
$dato=Post::getPostByIdPostAndEmail($email, $idPost);
if(!count($dato)) salir();
//todo bien puedo borrar
$imagen=$dato[0]->imagen;
Post::delete($idPost);
if(basename($imagen)!='noimage.jpg'){
    @unlink(".".$imagen);
}
header("Location:admin.php");



