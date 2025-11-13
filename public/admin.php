<?php

use App\Bd\Post;
use App\Utils\Datos;

session_start();
require __DIR__ . "/../vendor/autoload.php";
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit;
}
$email = $_SESSION['email'];
$misPosts = Post::getPostsByEmail($email);
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

<body class="bg-blue-200">
    <?php
    Datos::pintarNav("Inicio");
    ?>
    <h3 class="text-center text-xl font-bold mb-2">Mis Posts</h3>


    <div class="relative mx-auto w-3/4 shadow-md sm:rounded-lg">
        <div class="flex flex-row-reverse my-1">
            <a href="nuevo.php" class="p-2 rounded-lg bg-green-500 hover:bg-green-700 text-white font-bold">
                <i class="fas fa-add mr-1"></i>NUEVO
            </a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-16 py-3">
                        <span class="sr-only">Imagen</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Título
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Contenido
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Categoría
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Estado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($misPosts as $item):
                    $colorEstado = ($item->estado == 'Publicado') ? "bg-green-500" : "bg-red-500";
                ?>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="p-4">
                            <img src="<?= "." . $item->imagen ?>" class="w-16 md:w-32 max-w-full max-h-full" alt="Apple Watch">
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            <?= $item->titulo ?>
                        </td>
                        <td class="px-6 py-4 italic">
                            <?= $item->contenido ?>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-700 p-1 rounded-lg text-center"
                                style="background-color:<?= $item->color ?>"><?= $item->nombre ?></p>
                        </td>
                        <td class="px-6 py-4 italic">
                            <div class="flex items-center">
                                <div class="h-3 w-3 rounded-full me-2 <?= $colorEstado ?>"></div> <?= $item->estado ?>
                            </div>

                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="delete.php">
                                <input type="hidden" name="idPost" value="<?= $item->id ?>" />
                                <a href="update.php?id=<?= $item->id ?>">
                                    <i class="fas fa-edit text-blue-500 hover:text-xl mr-2"></i>
                                </a>
                                <button type='submit'>
                                    <i class="fas fa-trash text-red-500 hover:text-xl"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>


</body>

</html>