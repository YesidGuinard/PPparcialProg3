<?php


class User
{
    public $email;
    public $clave;

    public function __construct($object)
    {
        $this->email = $object['email'];
        $this->clave = $object['clave'];

    }


    public static function isValidUser($request)
    {

        $props = ['email', 'clave'];

        foreach ($props as $key => $prop)
            if (!isset($request[$prop]))
                return false;

        return true;
    }


    public static function getUser($prop, $dato)
    {

        $data = helper::leerArchivo('./archivos/users.json');

        foreach ($data as $key => $user)
            if (isset($user[$prop]) && $user[$prop] == $dato)
                return $user;

        return NULL;
    }

    public static function validateCredentials($email, $clave, $user)
    {
        return $user['email'] == $email && $user['clave'] == $clave;;
    }

}