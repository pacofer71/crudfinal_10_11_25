<?php

use App\Bd\Categoria;
use App\Bd\Post;
use App\Bd\Usuario;

require __DIR__."/../vendor/autoload.php";
Usuario::delete();
Categoria::delete();
Post::delete();

echo "Creando 5 Usuarios...\n";
Usuario::crearUsuarios(5);
echo "Usuarios Creados\n";
echo "\n";
echo "Creando Categorias...";
Categoria::crearCategorias();
echo "\nCategorias creadas.\n";
echo "Creando 50 Posts ...\n";
Post::crearPosts(50);
echo "\nPosts Creados.";