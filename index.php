<?php
require_once __DIR__ . "./vendor/autoload.php";

require_once './clases/User.php';
require_once './clases/helper.php';
require_once './clases/AuthJwt.php';
require_once './clases/Materia.php';
require_once './clases/Profesor.php';
require_once './clases/Asignacion.php';

$path_info = $_SERVER['PATH_INFO'] ?? NULL;
$request_method = $_SERVER['REQUEST_METHOD'] ?? NULL;
$message = '';
$success = false;


if (isset($request_method) && isset($path_info)) {
    if ($request_method == 'POST') {
        switch ($path_info) {
            case '/usuario':
                if (User::isValidUser($_POST)) {

                    $userFound = User::getUser('email', $_POST['email']);
                    if (isset($userFound)) {
                        $message = " Email ya registrado";
                    } else {
                        $user = new User($_POST);
                        $success = helper::guardarEnArchivo('./archivos/users.json', $user);
                        $message = $success ? "User registrado exitosamente" : "Error registrando el User";
                    }
                }
                break;
            case '/login':
                $email = $_POST['email'] ?? NULL;
                $clave = $_POST['clave'] ?? NULL;
                if (isset($email) && isset($clave)) {
                    //Devuelve el User si existe, o NULL en su defecto
                    $user = User::getUser('email', $email);
                    if (isset($user)) {
                        if (User::validateCredentials($email, $clave, $user) == true) {
                            $message = AuthJwt::generarJWT($user);
                            $success = true;
                        } else {
                            $message = "Clave incorrecta";
                        }
                    } else {
                        $message = "User no existe";
                    }
                } else {
                    $message = "Nombre o clave Vacios";
                }
                break;
            case '/materia':
                $usuario = AuthJwt::validarJWT();
                if (isset($usuario)) {
                    if (Materia::esMateriaValida($_POST)) {
                        $materia = new Materia($_POST);
                        $success = helper::guardarEnArchivo('./archivos/materias.json', $materia);
                        $message = $success ? "Materia registrado exitosamente" : "Error registrando Materia";
                    } else {
                        $message = "Materia vacio o nombre y cuatrimestre de Materia ya registrados)";
                    }
                } else {
                    $message = "User invalido (fail token)";
                }
                break;
            case '/profesor':
                $usuario = AuthJwt::validarJWT();
                if (isset($usuario)) {
                    if (Profesor::esProfesorValido($_POST)) {
                        $profesor = new Profesor($_POST);
                        $success = helper::guardarEnArchivo('./archivos/profesores.json', $profesor);
                        $message = $success ? "Profesor registrado exitosamente" : "Error registrando Profesor";
                    } else {
                        $message = "Profesor vacio o Legajo profesor ya registrados)";
                    }
                } else {
                    $message = "User invalido (fail token)";
                }
                break;
            case '/asignacion':
                $legajo = $_POST['legajo'] ?? NULL;
                $id = $_POST['id'] ?? NULL;
                $turno=$_POST['turno'] ?? NULL;
                if(isset($legajo) && isset($id)&&isset($turno)){
                    $usuario = AuthJwt::validarJWT();
                    if(isset($usuario)){
                        if (Asignacion::esAsignacionValida($_POST)) {
                            $asignacion = new Asignacion($_POST);
                            $success = helper::guardarEnArchivo('./archivos/materias-profesores.json', $asignacion);
                            $message = $success ? "Asignacion registrado exitosamente" : "Error registrando Asignacion";
                        } else {
                            $message = "Asignacion vacio o Legajo profesor ya registrados en turno)";
                        }

                    }else{
                        $message = "Asignacion no existe o no es tipo usuario Autorizado";
                    }
                }else{
                    $message = "Datos invalidos";
                }
                break;
            default:
                $message = "Ruta invalida";
        }
    } else if ($request_method == 'GET') {
        switch ($path_info) {
            case '/materia':
                $usuario = AuthJwt::validarJWT();
                if (isset($usuario)) {

                    $message = Materia::mostrarAllMaterias();
                    $success = true;
                }
                $message = $success ? $message : 'Error obteniendo el listado,(Credenciales Invalidas)';
                break;
            case '/profesor':
                $usuario = AuthJwt::validarJWT();
                if (isset($usuario)) {
                    $message = Profesor::mostrarProfesores();
                    $success = true;
                }
                $message = $success ? $message : 'Error obteniendo el listado profesores,(Credenciales Invalidas)';
                break;
            case '/asignacion':
                $usuario = AuthJwt::validarJWT();
                if (isset($usuario)) {
                    $message = Asignacion::mostrarAsignaciones();
                    $success = true;
                }
                $message = $success ? $message : 'Error obteniendo el listado Asignaciones,(Credenciales Invalidas)';
                break;
            default:
                $message = "Ruta invalida";
        }
    } else {
        $message = "Metodo no permitido";
    }
} else {
    $message = "Peticion invalida";
}

echo helper::formatResponse($message, $success);