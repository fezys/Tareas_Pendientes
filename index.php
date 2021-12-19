<?php 
    require_once 'login.php';
    $conexion = new mysqli($hn, $un, $pw, $db,$port);
    if($conexion->connect_error) die("Error fatal");

    session_start();
    if(isset($_SESSION['nombre']))
    {
      header('Location: Tareas_Pendientes.php');
      die("<a = href=cerrar.php>Cerrar Sesión </a>");
    }
    session_destroy();
    if (isset($_POST['username'])&&
        isset($_POST['password']))
    {
        $un_temp = mysql_entities_fix_string($conexion, $_POST['username']);
        $pw_temp = mysql_entities_fix_string($conexion, $_POST['password']);
        $query   = "SELECT * FROM users WHERE username='$un_temp'";
        $result  = $conexion->query($query);
        
        if (!$result) die ("Usuario no encontrado");
        elseif ($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();

            if (password_verify($pw_temp, $row[3])) 
            {
                session_start();
                $_SESSION['nombre']=$row[0];
                $_SESSION['apellido']=$row[1];
                $_SESSION['username']=$row[2];
                header('Location: Tareas_Pendientes.php');
            }
            else {
                echo "Usuario/password incorrecto <p><a href='registrar.php'>
            Registrarse</a></p>"; //si password es incorrecto
             echo "<p><a href='index.php'>
            Volver a intentar</a></p>";
            }
        }
        else {
          echo "Usuario/password incorrecto <p><a href='registrar.php'>  
      Registrarse</a></p>"; 
      echo "<p><a href='index.php'>
            Volver a intentar</a></p>";//si usuario esta incorrecto
      }   
    }
    else
    {
      echo " <h1> TAREAS PENDIENTES PANQUICITO </h1> ";
    echo "Panquicito es una pagina web donde puedes registrar tus tareas pendientes <br>";
      echo "
      <h1>Iniciar sesion</h1>
      <form action='index.php' method='post'><pre>
      Usuario  <input type='text' name='username'>
      Password <input type='password' name='password'>

               <input type='submit' value='INGRESAR'>
      </form>
      
      <br><a = href=registrar.php>REGISTRARSE</a>";
    }

    $conexion->close();

    function mysql_entities_fix_string($conexion, $string)
    {
        return htmlentities(mysql_fix_string($conexion, $string));
      }
    function mysql_fix_string($conexion, $string)
    {
        // if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return $conexion->real_escape_string($string);
    }  
?>