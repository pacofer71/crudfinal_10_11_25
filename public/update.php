<?php

use App\Bd\Categoria;
use App\Bd\Post;
use App\Bd\Usuario;
use App\Utils\Validacion;
require __DIR__ . "/../vendor/autoload.php";
session_start();
//Compruebo que estamos logeados
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit;
}
$email=$_SESSION['email'];
//comprobamos que mandamos por get el post a editar
$idPost=filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$idPost){
     header("Location:admin.php");
    exit;
}
//Comprobamos que el post a editar pertenecer al usuario
$datos=Post::getPostByIdPostAndEmail($email, $idPost);
if(!count($datos)){
    header("Location:admin.php");
    exit;
}
//Hemos pasado todas las comprobaciones.
$post=$datos[0];
$checkedPublicado=($post->estado=="Publicado") ? "checked" :"";
$checkedBorrador=($post->estado=="Borrador") ? "checked" :"";


$categorias = Categoria::read();
if (isset($_POST['titulo'])) {
    $titulo = Validacion::sanearCadenas($_POST['titulo']);
    $contenido = Validacion::sanearCadenas($_POST['contenido']);
    $category_id = Validacion::sanearCadenas($_POST['category_id']);
    $category_id = (int) $category_id;
    $estado = $_POST['estado'] ?? "Error";
    $estado = Validacion::sanearCadenas($estado);
    $errores = false;
    if (!Validacion::longitudCampoValida("titulo", $titulo, 3, 150)) {
        $errores = true;
    }
    if (!Validacion::longitudCampoValida("contenido", $contenido, 10, 450)) {
        $errores = true;
    }
    if (!Validacion::categoriaValida($category_id)) {
        $errores = true;
    }
    if (!Validacion::estadoValido($estado)) {
        $errores = true;
    }
    //Procesamos la imagen
    $imagen = $post->imagen;
    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
        $file = $_FILES['imagen'];
        if (!Validacion::imagenValida($file['type'], $file['size'])) {
            $errores = true;
        } else {
            //lo que hemos subido es una imagen y de tamaño apropiado
            //vamos a guardarla
            $nombreOriginal = $file['name'];
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $imagen = "/imagenes/" . uniqid() . ".$extension"; // /imagenes/12908af.jpg
            if (!move_uploaded_file($file['tmp_name'], ".$imagen")) {
                $_SESSION['err_imagen'] = "*** Error, NO se pudo guardar la imagen";
                $errores = true;
            }else{
                //La imagen se ha guardado, debemos decidir si borramos o no la vieja
                $imagenVieja=$post->imagen;
                if(basename($imagenVieja)!='noimage.jpg'){
                    @unlink(".".$imagenVieja);
                }
            }
        }
    }

    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}?id=$idPost");
        exit;
    }
    //editare elpost

    (new Post)
        ->setTitulo($titulo)
        ->setContenido($contenido)
        ->setCategoryId($category_id)
        ->setEstado($estado)
        ->setImagen($imagen)
        ->update($idPost);
        $_SESSION['mensaje']="Post modificado";
        header("Location:admin.php");
        exit;
}
?>
<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Tailwindcss -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDn SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>

<body class="p-8 bg-blue-200">
    <h3 class="text-center text-xl font-bold mb-2">EDITAR POST</h3>
    <div class="w-1/3 mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
        <form action="<?= $_SERVER['PHP_SELF']."?id=$idPost" ?>" method='POST' enctype="multipart/form-data">
            <!-- Campo titulo -->
            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-blue-500"></i>Título del Post
                </label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="<?= $post->titulo ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Título del Post">
                <?php Validacion::pintarError('err_titulo') ?>
            </div>

            <!-- Campo Contenido -->
            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-blue-500"></i>Contenido
                </label>
                <textarea
                    id="contenido"
                    name="contenido"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Contenido..."><?= $post->contenido ?></textarea>
                <?php Validacion::pintarError('err_contenido') ?>
            </div>

            <!-- Campo category_id -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-blue-500"></i>Categoria del Post
                </label>
                <select name='category_id' class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>____ Elige una categoria ____</option>
                    <?php foreach ($categorias as $item): 
                        $selected=($item->id==$post->category_id)? "selected" : "";
                    ?>
                        <option value="<?= $item->id ?>" <?= $selected ?>><?= $item->nombre ?></option>
                    <?php endforeach; ?>
                </select>
                <?php Validacion::pintarError('err_category_id') ?>
            </div>

            <!-- Radio Buttons Disponible -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-box mr-2 text-blue-500"></i>Estado
                </label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="estado"
                            value="Publicado"
                            <?= $checkedPublicado ?>
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Publicado</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="estado"
                            value="Borrador"
                            <?= $checkedBorrador ?>
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Borrador</span>
                    </label>
                </div>
                <?php Validacion::pintarError('err_estado') ?>
            </div>
            <!-- Campo Imagen -->
            <div class="mb-4 p-1 rounded-lg bg-green-100 relative w-full h-82">
                <input type="file" name="imagen" id="cimagen" class="hidden" accept="image/*" />
                <label for="cimagen" class="absolute bottom-2 right-2 p-1 rounded-lg bg-gray-700 hover:bg-gray-900 text-white font-bold">
                    <i class="fa-solid fa-upload mr-1"></i>SUBIR
                </label>
                <img src="<?= ".".$post->imagen ?>" id="preview" class="w-full h-full bg-center bg-cover bg-no-repeat" />
            </div>
            <?php Validacion::pintarError('err_imagen') ?>
            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a
                    href="admin.php"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition duration-200">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Editar
                </button>
            </div>
        </form>
    </div>
    <!-- Script para previsualizar la imagen elegida -->
    <script>
        const fileInput = document.getElementById('cimagen');
        const preview = document.getElementById('preview');
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>

</body>

</html>