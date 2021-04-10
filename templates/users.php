<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="assets/styles/base.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/navbar.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/table.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/modal.css" rel="stylesheet" media="screen" type="text/css"/>
    <script></script>
    <title>Users</title>
  </head>

  <body>

    <?php include_once("templates/header/navbar.html"); ?>

    <div class="text-center">
      
      <h1 class="section-header">Usuarios del sistema</h1>

      <br>

      <a href='./?action=view_user_new' class="button" title="Nuevo Usuario">
        <span class="button__text">Nuevo usuario</span>
      </a>

    </div>

    <div>
      <table class="styled-table center" id="tabla">
        <thead>
          <tr>
            <th>#</th>
            <th>Apellido</th>
            <th>Nombre</th>
            <th>Nombre de usuario</th>
            <th>Fecha creación</th>
            <th>Fecha actualización</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($params["users"] as $key => $user): ?>
            <tr>
              <td><?php echo $user["id"] ?></td>
              <td><?php echo $user["last_name"] ?></td>
              <td><?php echo $user["first_name"] ?></td>
              <td><?php echo $user["username"] ?></td>
              <td><?php echo $user["created_at"] ?></td>
              <td><?php echo $user["updated_at"] ?></td>
              <td>

                <form action="./?action=user_edit" method="post">

                  <input type="hidden" id="user_id" name="id" value="<?php echo $user["id"] ?>">

                  <button type="submit" class="button" title="Editar">
                    <span class="button__text">Editar</span>
                  </button>
                  
                </form>

                <br>

                <?php if ($user["id"] != $params["loggedUserId"]): ?>

                  <button name="btn-user-delete" data-user_id="<?php echo $user["id"] ?>" class="button is-danger" title="Eliminar">
                    <span class="button__text">Eliminar</span>
                  </button>

                <?php endif; ?>
              
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php include_once("templates/modals/modal.html"); ?>
      <script src="assets/js/script.js"></script>

    </div>
  </body>
</html>