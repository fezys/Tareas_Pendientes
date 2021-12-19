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

    if (isset($_POST['username']) && isset($_POST['password']))
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
        else
        {
          //si password es incorrecto
          echo "Usuario/Password incorrecto <p><a href='registrar.php'>Registrarse</a></p>"; 
          echo "<p><a href='index.php'>Volver a intentar</a></p>";
        }
      
      }
      else 
      {
        //si usuario esta incorrecto
        echo "Usuario/password incorrecto <p><a href='registrar.php'>Registrarse</a></p>"; 
        echo "<p><a href='index.php'>Volver a intentar</a></p>";
      }   
    }
    else
    {
      echo " <h1> <u>TAREAS PENDIENTES PANQUICITO </u></h1> ";
      echo "<FONT SIZE=5 COLOR='blue' >Registra tus tareas pendientes, y así los puedas recordar</FONT> <br>";
      echo "<h1>Iniciar sesion</h1>";

      echo "
      <form action='index.php' method='post'><pre>
      <input type='text' name='username' placeholder ='Usuario'>
      <input type='password' name='password' placeholder ='Contraseña' >

      <input type='submit' value='INGRESAR'>
      </form> <br> ";

      echo "<FONT SIZE=4 ><br>¿No tienes Una cuenta? <a = href=registrar.php >REGISTRARSE</a> </FONT>";
    }

    

    function mysql_entities_fix_string($conexion, $string)
    {
        return htmlentities(mysql_fix_string($conexion, $string));
      }
    function mysql_fix_string($conexion, $string)
    {
        // if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return $conexion->real_escape_string($string);
    } 
    $conexion->close();
?>