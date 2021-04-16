# stampy-test

Aplicación realizada con los lenguajes PHP, HTML, CSS y Vanilla JS

Para la gestión de la base de datos se utilizó MySQL

Para correr correctamente el sistema de una forma sencilla es necesario:

  - Instalar xampp
  - Habilitar la extensión pdo_mysql en el archivo php.ini
  - Clonar este repositorio en la carpeta htdocs del xampp
  - Iniciar el servidor de Apache y MySQL en el panel de control del xampp
  - Desde este mismo panel abrir phpMyAdmin y crear la base de datos stampy-test (los parámetros de la bd están en src/Repository/PDORepository)
  - Luego, dentro de esta BD importar el archivo stampy-test.sql que se encuentra en el proyecto y ejecutarlo.
  - Para acceder al sistema ingresar la url localhost/stampy-test
  
El sistema contiene 2 usuarios con los cuales se puede ingresar:

charly -> password: 12345678

daniel -> password: 12345678
