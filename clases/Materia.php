<?php

class Materia
{

    public $nombre;
    public $cuatrimestre;
    public $id;
    public static $path = './archivos/materias.json';

    public function __construct($obj)
    {

        $this->nombre = $obj['nombre'];
        $this->cuatrimestre = $obj['cuatrimestre'];
        $this->id = 'm' . time();

    }

    public static function esMateriaValida($request)
    {
        $props = ['nombre', 'cuatrimestre'];
        $materias = helper::leerArchivo(self::$path);

        foreach ($props as $key => $prop)
            if (!isset($request[$prop]))
                return false;

        $nombre = $request['nombre'];
        $cuatrimestre = $request['cuatrimestre'];

        if (!empty($materias))
            foreach ($materias as $key => $materia)
                if ($materia['nombre'] == $request['nombre'] && $materia['cuatrimestre'] == $request['cuatrimestre'])
                    return false;

        return true;
    }


    public static function getMateria($prop, $dato)
    {
        $data = helper::leerArchivo('./archivos/materias.json');

        foreach ($data as $key => $materia)
            if (isset($materia[$prop]) && $materia[$prop] == $dato)
                return $materia;

        return NULL;
    }

    public static function mostrarAllMaterias()
    {

        $materias = helper::leerArchivo(self::$path);

        if (empty($materias)) {
            return '[]';
        } else {
            return $materias;
        }
    }


}
