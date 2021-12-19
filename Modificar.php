<?php //signup.php
    session_start();
    require_once 'login.php';
    $conexion = new mysqli($hn, $un, $pw, $db,$port);
    if ($conexion->connect_error) die ("Fatal error de conexión");

    date_default_timezone_set("America/Lima");
    $fecha_actual =date("Y-m-d");
    $hora_actual =date("H:i");
    $date_actual = date("Y-m-d H:i");

    if (isset($_SESSION['nombre']))
    {
        $titulo = get_post($conexion,'titulo');
        
        if (isset($_POST['contenido']))
        {
            $contenido = mysql_entities_fix_string($conexion, $_POST['contenido']);
            $aux = new datetime(mysql_entities_fix_string($conexion, $_POST['fecha_vencimiento']));
            $aux2 = new datetime(mysql_entities_fix_string($conexion, $_POST['hora_vencimiento']));
            $aux3 = new DateTime($aux->format('Y-m-d') .' ' .$aux2->format('H:i'));
            $fecha_vencimiento = $aux3->format('Y-m-d H:i:s');       

            $query = "UPDATE tarea SET contenido='$contenido', fecha_vencimiento='$fecha_vencimiento' 
                WHERE titulo='$titulo'";
            $result = $conexion->query($query);
            if (!$result) die("Ocurrió Un ERROR al modificar ");

            header('Location: Tareas_Pendientes.php');
        }
        
        else
        {
            echo "
                <h1>Modificando la tarea: <FONT COLOR='blue'> $titulo </FONT></h1>

                <form action='Modificar.php' method='post' len><pre>

                <input type='hidden' name='titulo' value='$titulo'>
                Contenido            <textarea rows='3'  name='contenido' checked='cheked'></textarea>
                Fecha Vencimiento:   <input type='date' name='fecha_vencimiento'  min='$fecha_actual' max='2025-12-31' required='required'>
                Hora de Vencimiento: <input type ='time' name='hora_vencimiento' value='$hora_actual' required='required'>
                <input type='hidden' name='update' value='yes'>
                                     <input type='submit' value='MODIFICAR TAREA'>
                </form>";
                
                echo "<a href='Tareas_Pendientes.php'>CANCELAR</a>";
        }
    }
    else echo "Usted, no ha iniciado sesión <br>Por favor <a href=index.php>Click aqui</a> para ingresar";

    function get_post($con, $var)
    {
        return $con->real_escape_string($_POST[$var]);
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