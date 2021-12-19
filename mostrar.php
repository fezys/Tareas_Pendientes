<?php //continue.php
    session_start();
    require_once 'login.php';
    $conexion = new mysqli($hn, $un, $pw, $db,$port);
    if ($conexion->connect_error) die ("Fatal error");

    date_default_timezone_set("America/Lima");
    $fecha_actual =date("Y-m-d");
    $hora_actual =date("H:i");
    $date_actual = date("Y-m-d H:i");

    if (isset($_SESSION['nombre']))
    {
        if (isset($_POST['delete']) && isset($_POST['titulo']))
        {   
            $titulo = get_post($conexion,'titulo');
            $query = "DELETE FROM tarea WHERE titulo='$titulo'";
            $result = $conexion->query($query);
            if (!$result) echo "BORRAR falló";
        }
        //actualizar el estado de la tarea
        if (isset($_POST['archivar']) && isset($_POST['titulo']))
        {   
            $titulo = get_post($conexion,'titulo');
            $query = "UPDATE tarea SET estado='Archivado' WHERE titulo='$titulo'";
            $result = $conexion->query($query);
            if (!$result) echo "ARCHIVAR falló";
        }
        

        $nombre = htmlspecialchars($_SESSION['nombre']);
        $apellido = htmlspecialchars($_SESSION['apellido']);
        $username = htmlspecialchars($_SESSION['username']);

        echo "<h1>TAREAS PENDIENTES PANQUICITO </H1>
               <h3>Usuario $nombre $apellido  </h3>";
        echo "<h4>Menú de Tareas: ";
        echo "<a href=Tareas_Pendientes.php>Tareas Pendientes</a> ";
        echo "<a href=Tareas_Vencidas.php>Tareas Vencidas</a> ";
        echo "<a href=Tareas_Archivadas.php>Tareas archivadas</a> ";
        echo "<a href=mostrar.php>Todas las Tareas</a></h4>  " ;

        $query = "SELECT * FROM tarea where username = '$username' order by fecha_registro "; 
        $result = $conexion->query($query);
        if (!$result) die ("Consulta falló");
           
        $filas = $result->num_rows;

        if($filas==0)
        {
            die(" <FONT SIZE=6 COLOR='blue'>AUN NO REGISTRO NINGUNA TAREA</FONT><br><br>
                <a href='ingresar_tareas.php'>Agregar Tareas</a> <br><br>
                <a href=cerrar.php>Cerrar sesion</a>");
        }
        echo "<table BORDER=3 CELLPADDING=10 CELLSPACING=10> <tr> <th colspan=9><h1><FONT COLOR='blue'>TODAS LAS TAREAS REGISTRADAS</FONT></h1></th>
            </tr>  <tr><th>N° Registro</th><th>Titulo</th> <th>Contenido</th>
            <th>Fecha de Registro</th> <th>Fecha de vencimiento</th> 
            <th>Estado</th> <th>Archivar Tarea</th> <th>Modificar Tarea</th> <th>Eliminar</th></tr>";
        
        for ($j = 0; $j < $filas; $j++)
        {
            $fila = $result->fetch_array(MYSQLI_NUM);
            $titulo = htmlspecialchars($fila[0]);

            $date_vencimiento=htmlspecialchars($fila[3]);
            $estado=htmlspecialchars($fila[4]);

            if($date_vencimiento<=$date_actual){
                $query = "UPDATE tarea SET estado='Vencido' WHERE fecha_vencimiento='$date_vencimiento'
                    and estado ='Pendiente'";
                $resultado = $conexion->query($query);
                if (!$resultado) echo "Vencido falló";
                
            }
           
            echo "<tr>";

            //mostrando orden de registro
            $aux=$j+1;           
            echo "<td><FONT SIZE=6 COLOR='blue'><b>$aux °</b> </FONT></td>";

            for ($k = 0; $k <5; ++$k)
            echo "<td>". htmlspecialchars($fila[$k])." </td>";

            if($estado=='Pendiente')
            {
                echo "
                    <td >
                    <form action='mostrar.php' method='post'>
                    <input type='hidden' name='archivar' value='yes'>
                    <input type='hidden' name='titulo' value='$titulo'>
                    <input type='submit' value='Archivar Tarea'>
                    </form></td>";
            }
            else
            {
                echo "<td> <FONT COLOR='red'>No Disponible</FONT> </td>";
            }

            //modificar una tarea
            if($estado=='Pendiente')
            {
                        
                echo "
                <td >
                <form action='Modificar.php' method='post'>
                <input type='hidden' name='update' value='yes'>
                <input type='hidden' name='titulo' value='$titulo'>
                <input type='submit' value='Modificar Tarea'>
                </form></td>";
            }
            else
            {
                echo "<td> <FONT COLOR='red'>No Disponible</FONT> </td>";
            }           


            echo "
                <td >
                <form action='mostrar.php' method='post'>
                <input type='hidden' name='delete' value='yes'>
                <input type='hidden' name='titulo' value='$titulo'>
                <input type='submit' value='Eliminar Tarea'>
                </form></td>";      
                   
            echo "</tr>";
        }
        echo "</table>";

        echo "<br><br>";
        echo "<a href='ingresar_tareas.php'>Agregar Tareas</a><br><br>";
        echo "<a href=cerrar.php>Cerrar sesion</a> ";
        
        $result->close();
    }
    else echo "Por favor <a href=index.php>Click aqui</a>
                para ingresar";

    
    $conexion->close();
            
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
