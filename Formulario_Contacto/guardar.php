<?php
    include("conexion.php");
    
    $nombre=$_POST['nombre'];
    $correo=$_POST['correo']; 
    $mensaje=$_POST['mensaje']; 

    $sql="INSERT INTO datos VALUES ('$nombre','$correo','$mensaje')";

    $ejecutar=mysqli_query($conexion, $sql);

    if(!$ejecutar){
        echo '<script language="javascript">alert("Ocurrio un error");window.location.href="../index.php"</script>';
    }else{
        echo '<script language="javascript">alert("Mensaje enviado con exito");window.location.href="../index.php"</script>';
    }
?>