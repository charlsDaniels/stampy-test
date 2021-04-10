<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="assets/styles/base.css" rel="stylesheet" media="screen" type="text/css"/>
    <title>Login</title>
  </head>
  <body>
    
    <?php if ($params["error"]): ?>

      <p class="is-danger"><?php echo $params["error"] ?></p>
      
    <?php endif; ?>

    <div class="box">
      
      <h1>Inicio de sesión</h1>
      <form action="./?action=login" method="post">

        <label for="username">Nombre de usuario</label>
        <input id="username" type="text" name="username" required>
        
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" required>

        <button class="button margin-top" type="submit">
          <span class="button__text">Aceptar</span>
        </button>

      </form>
    </div> <!-- End Box -->
             
  </body>
</html>
