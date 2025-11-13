<?php

namespace App\Bd;

use App\Utils\Datos;
use \PDO;
use \PDOException;

class Post extends Conexion
{
    private int $id;
    private string $titulo;
    private string $contenido;
    private string $estado;
    private string $imagen;
    private int $user_id;
    private int $category_id;

    private static function executeQuery(string $q, array $param = []): \PDOStatement
    {
        $stmt = self::getConexion()->prepare($q);
        try {
            $stmt->execute($param);
            return $stmt;
        } catch (PDOException $ex) {
            throw new PDOException("Error en la consulta: " . $ex->getMessage());
        }
    }
    public function create()
    {
        $q = "insert into posts(titulo, contenido, estado, imagen, user_id, category_id) values(:t, :c, :e, :im, :ui, :ci)";
        self::executeQuery($q, [
            ':t' => $this->titulo,
            ':c' => $this->contenido,
            ':e' => $this->estado,
            ':im' => $this->imagen,
            ':ui' => $this->user_id,
            ':ci' => $this->category_id,

        ]);
    }
    public function update(int $id)
    {
        //el user_id en este caso no lo vamos a actualizar
        $q = "update posts set titulo=:t, contenido=:c, estado=:e, imagen=:im, category_id=:ci where id=:i";
        self::executeQuery($q, [
            ':t' => $this->titulo,
            ':c' => $this->contenido,
            ':e' => $this->estado,
            ':im' => $this->imagen,
            ':ci' => $this->category_id,
            ':i' => $id
        ]);
    }
    public static function delete(?int $id = null)
    {
        $q = ($id == null) ? "delete from posts" : "delete from posts where id=:i";
        $param = ($id == null) ? [] : [':i' => $id];
        self::executeQuery($q, $param);
    }
    public static function readAll(): array
    {
        $q = "select posts.*, email, nombre, color from posts, categorias, usuarios where
            usuarios.id=user_id AND categorias.id=category_id  AND estado='Publicado' order by posts.id desc";
        $stmt = self::executeQuery($q);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public static function getPostsByEmail(string $email): array
    {
        $q = "select posts.*, email, nombre, color from posts, categorias, usuarios
         where usuarios.id=user_id AND categorias.id=category_id AND email=:e order by posts.id desc";
        $stmt = self::executeQuery($q, [':e' => $email]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function getPostByIdPostAndEmail(string $email, int $idPost): array
    {
        $q = "select posts.* from posts, usuarios where posts.user_id=usuarios.id AND email=:e AND posts.id=:ip";
        $stmt = self::executeQuery($q, [':e' => $email, ':ip' => $idPost]);
        return $stmt->fetchAll(PDO::FETCH_OBJ); // [] [obj(id, titulo, contenido...)]
    }


    public static function crearPosts(int $cant)
    {
        $faker = \Faker\Factory::create('es_ES');
        $categoriasId = Categoria::getIdsCategorias();
        $usuariosId = Usuario::getIdsUsuarios();
        for ($i = 0; $i < $cant; $i++) {
            $titulo = $faker->sentence(4, true);
            $contenido = $faker->text();
            $estado = $faker->randomElement(['Publicado', 'Borrador']);
            $imagen = "/imagenes/noimage.jpg";
            $user_id = $faker->randomElement($usuariosId);
            $category_id = $faker->randomElement($categoriasId);
            (new Post)
                ->setTitulo($titulo)
                ->setContenido($contenido)
                ->setImagen($imagen)
                ->setEstado($estado)
                ->setCategoryId($category_id)
                ->setUserId($user_id)
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
     * Set the value of titulo
     */
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Set the value of contenido
     */
    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Set the value of estado
     */
    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Set the value of user_id
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Set the value of category_id
     */
    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}
