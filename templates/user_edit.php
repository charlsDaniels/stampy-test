<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="assets/styles/base.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/navbar.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/modal.css" rel="stylesheet" media="screen" type="text/css"/>
    <title>Login</title>
  </head>
  <body>

    <?php include_once("templates/header/navbar.html"); ?>

    <?php if ($params["msg"]): ?>

      <p class="is-danger"><?php echo $params["msg"] ?></p>
      
    <?php endif; ?>

    <div class="box">
      
      <h1>Editar usuario</h1>
      <form action="./?action=user_update" method="post">

        <input type="hidden" name="id" value="<?php echo $params['user']['id'] ?>">

        <label for="last_name">Apellido</label>
        <input id="last_name" type="text" name="last_name" value="<?php echo $params['user']['last_name'] ?>" required>
        
        <label for="first_name">Nombre</label>
        <input id="first_name" type="text" name="first_name" value="<?php echo $params['user']['first_name'] ?>" required>
        
        <label for="email">Email</label>
        <input id="email" type="text" name="email" value="<?php echo $params['user']['email'] ?>" required>

        <label for="username">Nombre de usuario</label>
        <input id="username" type="text" name="username" value="<?php echo $params['user']['username'] ?>" required>
        
        <a href="#" id="btn-user-pass-change" data-user_id="<?php echo $params['user']['id'] ?>">
          Cambiar contraseña
        </a>
          
        <br>
        
        <button class="button margin-top" type="submit">
          <span class="button__text">Aceptar</span>
        </button>

      </form>

    </div>

    <div>

      <a href='./?action=users' class="button" title="Volver">
        <span class="button__text">Volver</span>
      </a>

    </div>

    <?php include_once("templates/modals/modal_pass.html"); ?>
    <script src="assets/js/modal_pass.js"></script>

  </body>
</html>
