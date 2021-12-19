<?php //logout.php
//cerrar sesión
    session_start();

    if (isset($_SESSION['nombre']))
    {
        $nombre = $_SESSION['nombre'];
        $apellido = $_SESSION['apellido'];

        destroy_session_and_data();

        header('Location: index.php');
        die();
    }
    else echo "Por favor <a href='index.php'>Click aqui</a>
                para iniciar sesion";

    function destroy_session_and_data()
    {
        //session_start();
        $_SESSION = array();
        setcookie(session_name(), '', time()-2592000, '/');
        session_destroy();
    }
?>