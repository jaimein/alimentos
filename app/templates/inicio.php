<?php ob_start()?>
<h1>Inicio</h1>
<h3> Fecha: <?php echo $params['fecha'] ?> </h3>
<?php echo $params['mensaje'] ?>

<form action="index.php?ctl=login" method="post" name="login_form" style="text-align: center">
    <table>
        <tr>
            <th colspan="2">
                <h1>Login</h1>
            </th>
        </tr>
        <tr>
            <td>Usuario: </td>
            <td><input type="text" name="usuario" /></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" id="password" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" class="boton-login" value="Registro" onclick="location.href='index.php?ctl=registro'">
                <input type="submit" class="boton-login" value="Login" />
            </td>
        </tr>
    </table>
</form>

<?php $contenido = ob_get_clean()?>
<?php include 'layout_basico.php'?>