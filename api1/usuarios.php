<?php
require "config/Conexion.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers:Content-Type");
$datos = json_decode(file_get_contents('php://input'), true);
//print_r($_SERVER['REQUEST_METHOD']);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
        // Habilitar CORS para cualquier origen
   header("Access-Control-Allow-Origin: *");
   
   // Permitir métodos HTTP específicos
   header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE, HEAD, TRACE, PATCH");
   
   // Permitir encabezados personalizados
   header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
   
   // Permitir credenciales
   header("Access-Control-Allow-Credentials: true");
   
   if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
       // Responder a la solicitud OPTIONS sin procesar nada más
       http_response_code(200);
       exit;
   }
   
           
           break;
   case 'HEAD':
     if ($_SERVER['REQUEST_METHOD'] === 'HEAD') {
       // Establecer encabezados de respuesta
       header('Content-Type: application/json');
       header('Custom-Header: PHP 8, HTML ');
   
       // Puedes establecer otros encabezados necesarios aquí
   
       // No es necesario enviar un cuerpo en una solicitud HEAD, por lo que no se imprime nada aquí.
   } else {
       http_response_code(405); // Método no permitido
       echo 'Método de solicitud no válido';
   }
     break;
   
     case 'TRACE':
       header("Access-Control-Allow-Origin: *");
       if ($_SERVER['REQUEST_METHOD'] === 'TRACE') {
         $response = "Solicitud TRACE recibida. Estado: 200 OK";
     } else {
         $response = "Método de solicitud no válido. Estado: 405 Método no permitido";
     }
     
     echo $response;
       break;
   


    case 'GET':
        // Consulta SQL para seleccionar datos de la tabla de entradas_diario
        $sql = "SELECT id_usuario, nombre_usuario, contrasena, correo_electronico FROM usuarios";

        $query = $conexion->query($sql);

        if ($query->num_rows > 0) {
            $data = array();
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
            // Devolver los resultados en formato JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo "No se encontraron registros en la tabla de entradas_diario.";
        }

        $conexion->close();
        break;

    case 'POST':
            // Recibir los datos del formulario HTML
            $nombre_usuario = $datos['nombre_usuario'];
            $contrasena = $datos['contrasena'];
            $correo_electronico = $datos['correo_electronico'];

            // Insertar los datos en la tabla de entradas_diario
            $sql = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, contrasena, correo_electronico) VALUES (?,?,?)");
            $sql->bind_param("sss", $nombre_usuario, $contrasena, $correo_electronico);
            if($sql->execute()){
                echo "Datos insertados con exito";
            } else {
                echo "Error al insertar datos" . $sql->error;
            }
        $sql->close();
        break;

        case 'PATCH':
        $id_usuario = $datos['id_usuario'];
        $nombre_usuario = $datos['nombre_usuario'];
        $contrasena = $datos['contrasena'];
        $correo_electronico = $datos['correo_electronico'];
    
        $actualizaciones = array();
        if (!empty($contrasena)) {
            $actualizaciones[] = "nombre_usuario = '$nombre_usuario'";
        }
        if (!empty($contrasena)) {
            $actualizaciones[] = "contrasena = '$contrasena'";
        }
        if (!empty($correo_electronico)) {
            $actualizaciones[] = "correo_electronico = '$correo_electronico'";
        }
    
        $actualizaciones_str = implode(', ', $actualizaciones);
        $sql = "UPDATE usuarios SET $actualizaciones_str WHERE id_usuario = $id_usuario";
    
        if ($conexion->query($sql) === TRUE) {
            echo "Registro actualizado con éxito.";
        } else {
            echo "Error al actualizar registro: " . $conexion->error;
        }
        break;
    

        case 'PUT':
            $nombre_usuario = $datos['nombre_usuario'];
            $contrasena = $datos['contrasena'];
            $correo_electronico = $datos['correo_electronico'];
            $id_usuario = $datos['id_usuario'];
            $sql = "UPDATE usuarios SET nombre_usuario = '$nombre_usuario', contrasena = '$contrasena', correo_electronico = '$correo_electronico' WHERE id_usuario = $id_usuario";
    
            if ($conexion->query($sql) === TRUE) {
                echo "Registro actualizado con éxito.";
            } else {
                echo "Error al actualizar registro: " . $conexion->error;
            }
            break;
    

            case 'DELETE':
                $id_usuario = $_GET['id_usuario'];
                $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = $id_usuario");
                
                if ($stmt->execute()) {
                    echo "Registro eliminado con éxito.";
                } else {
                    echo "Error al eliminar registro: " . $stmt->error;
                }
                $stmt->close();
                break;
                    
            default:
                echo "Método de solicitud no válido.";
                break;
        }

?>