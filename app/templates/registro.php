<?php ob_start()?>

<form action="index.php?ctl=registro" method="post" name="login_form" style="text-align: center">
    <table>
        <tr>
            <th colspan="2">
                <h1>Registro</h1>
            </th>
        </tr>
        <?php if (isset($params['mensaje'])): ?>
        <tr>
            <td colspan="2">

                <b><span style="color: red;"><?php echo $params['mensaje'] ?></span></b>

            </td>
        </tr>
        <?php endif;?>
        <tr>
            <td>Usuario: </td>
            <td><input type="text" name="usuario" value="<?php echo $params['usuario'] ?>"/></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" id="password" value="<?php echo $params['password'] ?>"/></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="email" id="email" value="<?php echo $params['email'] ?>"/></td>
        </tr>
        <tr>
            <td>Ciudad:</td>
            <td><input type="text" name="ciudad" id="ciudad" value="<?php echo $params['ciudad'] ?>"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" class="boton-login" value="Atras" onclick="location.href='index.php?ctl=inicio'">
                <input type="submit" class="boton-login" value="Crear" />
            </td>
        </tr>
    </table>
</form>

<h3>No introducir espacios, en usuario y contraseña</h3>

<?php $contenido = ob_get_clean()?>
<?php include 'layout_basico.php'?>