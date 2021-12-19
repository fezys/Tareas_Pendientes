<?php //continue.php
    session_start();
    require_once 'login.php';
    $conexion = new mysqli($hn, $un, $pw, $db,$port);
    if ($conexion->connect_error) die ("Fatal error");

    if (isset($_SESSION['nombre']))
    {
        //Eliminar una Tarea
        if (isset($_POST['delete']) && isset($_POST['titulo']))
        {   
            $titulo = get_post($conexion,'titulo');
            $query = "DELETE FROM tarea WHERE titulo='$titulo'";
            $result = $conexion->query($query);
            if (!$result) echo "BORRAR falló";
        }
        $nombre = htmlspecialchars($_SESSION['nombre']);
        $apellido = htmlspecialchars($_SESSION['apellido']);
        $username = htmlspecialchars($_SESSION['username']);

        echo "<h1>TAREAS PENDIENTES PANQUICITO </H1>
               <h3>Usuario $nombre $apellido  </h3>";
        echo "<h4>Menú de Tareas:  ";
        echo "<a href=Tareas_Pendientes.php>Tareas Pendientes</a> ";
        echo "<a href=Tareas_Vencidas.php>Tareas Vencidas</a> ";
        echo "<a href=Tareas_Archivadas.php>Tareas archivadas</a> ";
        echo "<a href=mostrar.php>Todas las Tareas</a></h4>  " ;

               $query = "SELECT * FROM tarea where estado = 'vencido' and username = '$username' 
               order by fecha_vencimiento"; 
               $result = $conexion->query($query);
               if (!$result) die ("Consulta falló");
           
               $filas = $result->num_rows;

               if($filas==0){
                   die(" <FONT SIZE=6 COLOR='red'>NO TIENE TAREAS VENCIDAS </FONT>
                   <br><br>
                   <a href='ingresar_tareas.php'>Agregar Tareas</a> <br><br>
                   <a href=cerrar.php>Cerrar sesion</a>");
               }
           
               echo "<table BORDER=3 CELLPADDING=10 CELLSPACING=10> <tr> <th colspan=8><h1><FONT COLOR='red'>TAREAS VENCIDAS</FONT></h1></th> 
                    </tr><tr><th>Titulo</th> <th>Contenido</th>
                    <th>Fecha de Registro</th>   <th>Fecha de vencimiento</th>
                    <th>Estado</th>  <th>Eliminar</th></tr>";
           
               for ($j = 0; $j < $filas; $j++)
               {
                   $fila = $result->fetch_array(MYSQLI_NUM);
                   $titulo = htmlspecialchars($fila[0]);
           
                   echo "<tr>";
                   for ($k = 0; $k <5; ++$k)
                       echo "<td>". htmlspecialchars($fila[$k]). "</td>";

                       //Eliminar una tarea
                       echo "
                       <td >
                       <form action='Tareas_Vencidas.php' method='post'>
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
        $conexion->close();
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
            
        return $conexion->real_escape_string($string);
    } 
?>