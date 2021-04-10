<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="assets/styles/base.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/navbar.css" rel="stylesheet" media="screen" type="text/css"/>
    <title>Login</title>
  </head>
  <body>

    <?php include_once("templates/header/navbar.html"); ?>

    <?php if ($params["error"]): ?>

      <p class="is-danger"><?php echo $params["error"] ?></p>
      
    <?php endif; ?>

    <div class="box">
      
      <h1>Nuevo usuario</h1>
      <form action="./?action=user_new" method="post">

        <label for="last_name">Apellido</label>
        <input id="last_name" type="text" name="last_name" required>
        
        <label for="first_name">Nombre</label>
        <input id="first_name" type="text" name="first_name" required>

        <label for="email">Email</label>
        <input id="email" type="text" name="email" required>

        <label for="username">Nombre de usuario</label>
        <input id="username" type="text" name="username" required>

        <label for="password">Contraseña</label>
        <input id="password" type="text" name="password" required>

        <button class="button margin-top" type="submit">
          <span class="button__text">Aceptar</span>
        </button>

      </form>
    </div> <!-- End Box -->

    <div>

      <a href='./?action=users' class="button" title="Volver">
        <span class="button__text">Volver</span>
      </a>

    </div>
             
  </body>
</html>
