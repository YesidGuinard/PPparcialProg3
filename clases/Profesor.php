<?php

class Profesor
{

    public $nombre;
    public $legajo;
    public $foto;
    public static $path = './archivos/profesores.json';

    public function __construct($obj)
    {

        $this->nombre = $obj['nombre'];
        $this->legajo = $obj['legajo'];
        $this->foto = helper::procesarImagen($_FILES['imagen']);
    }


    public static function esProfesorValido($request)
    {

        $props = ['nombre', 'legajo'];
        $profesores = helper::leerArchivo(self::$path);

        foreach ($props as $key => $prop)
            if (!isset($request[$prop]))
                return false;

        if (!empty($profesores))
            foreach ($profesores as $key => $profesor)
                if ($profesor['legajo'] == $request['legajo'])
                    return false;

        return true;
    }


    public static function getProfesor($prop, $dato)
    {
        $data = helper::leerArchivo('./archivos/profesores.json');

        foreach ($data as $key => $profesor)
            if (isset($profesor[$prop]) && $profesor[$prop] == $dato)
                return $profesor;

        return NULL;
    }

    public static function mostrarProfesores()
    {
        $profesores = helper::leerArchivo(self::$path);
        if (empty($profesores)) {
            return '[]';
        } else {
            return $profesores;
        }
    }

}
