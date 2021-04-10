<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link href="assets/styles/style.css" rel="stylesheet" media="screen" type="text/css"/>
    <link href="assets/styles/login.css" rel="stylesheet" media="screen" type="text/css"/>

    <title>Users</title>
  </head>
  <body>

    <div class="text-center">
      <h1>Usuarios del sistema</h1>
    </div>

    <div>

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

                  <input type="hidden" name="id" value="<?php echo $user["id"] ?>">

                  <button type="submit" class="button" title="Editar">
                    <span class="button__text">Editar</span>
                  </button>
                  
                </form>

                <br>

                <button class="button is-danger" title="Eliminar">
                  <span class="button__text">Eliminar</span>
                </button>
              
                </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </body>
</html>