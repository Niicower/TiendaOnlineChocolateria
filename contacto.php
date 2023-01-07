<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Formulario_Contacto/contacto2.css">
    <title>Contacto</title>
</head>
<body>
<div class="contenedor">
        
        <div class="contenido">
            <div class="info">
                <div class="col">
                    <i class="icono fas fa-map-marker-alt"></i>
                    <p>Valdivia</p>
                </div>

                <div class="col">
                    <i class="icono fas fa-envelope"></i>
                    <p></p>
                </div>

                <div class="col">
                    <i class="icono fas fa-phone"></i>
                    <p>+569-</p>
                </div>
            </div>

            <form action="Formulario_Contacto/guardar.php" method="post" class="formulario">
                <label for="nombre"></label>
                <input type="text" name="nombre" placeholder="Nombre">
                <label for="correo"></label>
                <input type="email" name="correo" placeholder="Correo electróico">
                
                <label for="mensaje"></label>
                <br>
                <textarea rows="7" text-align: center name="mensaje" placeholder="Mensaje" required=""></textarea>              
                <br>
                <input type="submit" value="enviar">
                <button class="limpiar" type="reset">Limpiar</button>
            </form>
        </div>
    </div>
</body>
</html>