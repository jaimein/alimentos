<?php
include_once ('Config.php');

class Model extends PDO
{

    protected $conexion;

    public function __construct()
    {
        
            $this->conexion = new PDO('mysql:host=' . Config::$mvc_bd_hostname . ';dbname=' . Config::$mvc_bd_nombre . '', Config::$mvc_bd_usuario, Config::$mvc_bd_clave);
            // Realiza el enlace con la BD en utf-8
            $this->conexion->exec("set names utf8");
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
    }

   

    public function dameAlimentos()
    {
        
            $consulta = "select * from alimentos order by energia desc";
            $result = $this->conexion->query($consulta);
            return $result->fetchAll();
           
       
    }

    public function buscarAlimentosPorNombre($nombre)
    {
       
        $consulta = "select * from alimentos where nombre like :nombre order by energia desc";
        
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(':nombre', $nombre);
        $result->execute();
           
        return $result->fetchAll();
        
    }
    
    public function dameAlimento($id)
    {
        
            $consulta = "select * from alimentos where id=:id";
            
            $result = $this->conexion->prepare($consulta);
            $result->bindParam(':id', $id);
            $result->execute();
            return $result->fetch();
            
        
    }
    
    
    public function insertarAlimento($n, $e, $p, $hc, $f, $g)
    {
        $consulta = "insert into alimentos (nombre, energia, proteina, hidratocarbono, fibra, grasatotal) values (?, ?, ?, ?, ?, ?)";
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(1, $n);
        $result->bindParam(2, $e);
        $result->bindParam(3, $p);
        $result->bindParam(4, $hc);
        $result->bindParam(5, $f);
        $result->bindParam(6, $g);
        $result->execute();
                
        return $result;
    }

    public function insertarUsuario($usuario, $password, $nivel, $email, $ciudad)
    {
        $consulta = "INSERT INTO `users`(`user`, `pass`, `nivel`, `email`, `ciudad`) VALUES (?,?,?,?,?)";
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(1, $usuario);
        $result->bindParam(2, $password);
        $result->bindParam(3, $nivel);
        $result->bindParam(4, $email);
        $result->bindParam(5, $ciudad);
        $result->execute();
                
        return $result;
    }

    public function comprobar_si_existe_usuario($usu){
        $consulta = "SELECT * FROM `users` where user like :nombre";
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(':nombre', $nombre);
        $result->execute();
           
        if($result->fetch()){
            return false;
        } else {
            return true;
        }
    }

    public function obtener_datos_usu($usuario, $password){
        $consulta = "SELECT `id`, `user`, `pass`, `nivel`, `email`, `ciudad` FROM `users` where user like :nombre and pass like :pass";
        $result = $this->conexion->prepare($consulta);
        $result->bindParam(':nombre', $usuario);
        $result->bindParam(':pass', $password);
        $result->execute();
        return $result->fetch();
    }
}
?>
