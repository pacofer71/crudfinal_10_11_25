<?php

use App\Bd\Post;

require __DIR__ . "/../vendor/autoload.php";
$posts = Post::readAll();
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
    <h3 class="text-center text-xl font-bold mb-2">POSTS</h3>
    <!-- Grid contenedor: 3 columnas responsivas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
        <?php foreach($posts as $item): ?> 
        <!-- Card de ejemplo -->
        <article class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 group">
            <!-- Imagen de fondo -->
            <img
                src="<?=".".$item->imagen ?>"
                alt="Post cover"
                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

            <!-- Capa semitransparente para que el texto se lea -->
            <div class="absolute inset-0 bg-black/40"></div>

            <!-- Contenido -->
            <div class="relative z-10 flex flex-col justify-end h-80 p-5 text-white">
                <!-- Título -->
                <h2 class="text-xl font-semibold mb-2"><?=$item->titulo?></h2>

                <!-- Contenido en cursiva -->
                <p class="text-sm italic mb-3 line-clamp-2">
                    <?= $item->contenido ?>
                </p>

                <!-- Email -->
                <p class="text-xs opacity-80 hover:opacity-100 transition-opacity">
                    <?=$item->email ?>
                </p>

                <!-- Categoría -->
                <span class="mt-2 inline-block self-start px-3 py-1 text-xs font-bold rounded-full text-gray-800" 
                style="background-color:<?=$item->color ?>">
                    <?= $item->nombre ?>
                </span>
            </div>
        </article>
        <?php endforeach; ?>

    </div>
</body>

</html>