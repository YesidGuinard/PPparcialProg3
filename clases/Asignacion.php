<?php

class Asignacion
{
    public $turno;
    public $id;
    public $legajo;

    public static $path = './archivos/materias-profesores.json';

    public function __construct($obj)
    {
        $this->turno = $obj['turno'];
        $this->id = $obj['id'];
        $this->legajo = $obj['legajo'];
    }


    public function registrarAsignacion(){

        return helper::guardarEnArchivo(self::$path,$this);
    }

    public static function esAsignacionValida($request)
    {

        $props = ['turno', 'id', 'legajo'];
        $asignaciones = helper::leerArchivo(self::$path);

        foreach ($props as $key => $prop)
            if (!isset($request[$prop]))
                return false;

        $turno = $request['turno'];

        if ($turno != 'manana' && $turno != 'noche') return false;

        if (!empty($asignaciones))
            foreach ($asignaciones as $key => $asignacione)
                if ($asignacione['legajo'] == $request['legajo'] && $asignacione['turno'] == $request['turno'] && $asignacione['id'] == $request['id'])
                    return false;

        return true;
    }

    public static function mostrarAsignaciones()
    {
        $asignaciones = helper::leerArchivo(self::$path);
        if (empty($asignaciones)) {
            return '[]';
        } else {
            return $asignaciones;

        }

    }

}
