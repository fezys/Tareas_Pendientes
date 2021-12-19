<?php //signup.php
    require_once 'login.php';
    $conexion = new mysqli($hn, $un, $pw, $db,$port);
    if ($conexion->connect_error) die ("Fatal error");

    if(isset($_POST['username']) && isset($_POST['password']))
    {
        $nombre = mysql_entities_fix_string($conexion, $_POST['nombre']);
        $apellido = mysql_entities_fix_string($conexion, $_POST['apellido']);
        $username = mysql_entities_fix_string($conexion, $_POST['username']);
        $pw_temp = mysql_entities_fix_string($conexion, $_POST['password']);

        $password = password_hash($pw_temp, PASSWORD_DEFAULT);

        $query = "INSERT INTO users
            VALUES('$nombre','$apellido','$username', '$password')";

        $result = $conexion->query($query);
        if (!$result) die ("Falló registro pruebe con otro nombre de Usuario <br> <a href='registrar.php'>Registrarse</a>");

        echo "Registro exitoso <a href='index.php'>Ingresar</a>";
        
    }
    else
    {
        echo "<h1> <u>Regístrate en PANQUICITO </u></h1>";
        echo "
        <form action='registrar.php' method='post' len><pre>

        <input type='text' name='nombre' placeholder ='Ingrese Su Nombre' required='required'>
        <input type='text' name='apellido' placeholder ='Ingrese su Apellido' required='required'>
        <input type='text' name='username' placeholder ='Nombre de usuario' required='required'>
        <input type='password' name='password' placeholder ='Ingrese su contraseña' required='required'>
        <input type='hidden' name='reg' value='yes'> 
        <input type='submit' value='REGISTRAR'></pre>
        </form>";
        
        echo "<a href='index.php'>Iniciar Sesión</a>";
        
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
?>