<?php

namespace App\Bd;

use App\Utils\Datos;
use \PDO;
use \PDOException;

class Categoria extends Conexion
{
    private int $id;
    private string $nombre;
    private string $color;

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
    public static function read(): array{
        $q="select * from categorias order by nombre";
        $stmt=self::executeQuery($q);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create()
    {
        $q = "insert into categorias(nombre, color) values(:n, :c)";
        self::executeQuery($q, [
            ':n' => $this->nombre,
            ':c' => $this->color,
        ]);
    }
    public static function delete(?int $id = null)
    {
        $q = ($id == null) ? "delete from categorias" : "delete from categorias where id=:i";
        $param = ($id == null) ? [] : [':i' => $id];
        self::executeQuery($q, $param);
    }
    public static function getIdsCategorias(): array
    {
        $q = "select id from categorias";
        $stmt = self::executeQuery($q);
        $datos = $stmt->fetchAll(PDO::FETCH_OBJ);
        $cats = [];
        foreach ($datos as $item) {
            $cats[] = $item->id;
        }
        return $cats;
    }


    public static function crearCategorias()
    {
        foreach (Datos::CATEGORIAS as $nombre => $color) {
            (new Categoria)
                ->setNombre($nombre)
                ->setColor($color)
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
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of color
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
