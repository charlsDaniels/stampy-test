<?php

require_once './src/Repository/UserRepository.php';

class UserController extends MainController {

  public function viewLogin($params = array("error" => '')) {
    $this->render('login', $params);
  }

  public function viewUserNew($params = array("error" => '')) {
    $this->render('user_new', $params);
  }

  public function login() {

    $this->prepareData(array('username', 'password'));

    if ($this->postElementsCheck(array('username', 'password'))) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $repo = new UserRepository();
      $user = $repo->login($username);
      if (!empty($user)) {
        if (password_verify($password, $user['password'])){
          $this->startUserSession($user);
          $this->redirectTo('users');
        } else {
          $this->viewLogin(array("error" => 'Contraseña incorrecta'));
        }
      }else{
        $this->viewLogin(array("error" => 'No existe ese usuario'));
      }
    }else {
      $this->viewLogin(array("error" => 'Faltó completar alguno de los datos'));
    }

  }

  private function startUserSession($user){
    session_destroy();
    session_set_cookie_params(0);
    session_start();
    $_SESSION['id']= $user['id'];
    $_SESSION['username']= $user['username'];
  }

  public function logout(){
    session_destroy();
    $_SESSION= array();
    $this->viewLogin();
  }

  //ejecuta la creación de un nuevo usuario.
  public function userNew() {

    if($this->isLoggedUser()) { //es un usuario logueado

      $this->prepareData(array('last_name', 'first_name', 'email', 'username', 'password'));

      if ($this->postElementsCheck(array('last_name', 'first_name', 'email', 'username', 'password'))) {

        $lastName = $_POST['last_name'];
        $firstName = $_POST['first_name'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
      
        $err = $this->isValidForm(
          $lastName, 
          $firstName, 
          $email, 
          $username,
          $password 
        );

        if (empty($err)){
          $user_repo= new UserRepository();
          if($user_repo->checkUserName($username)){
            if($user_repo->checkEmail($email)) {
              $user = $user_repo->newUser(
                $lastName, 
                $firstName, 
                $email, 
                password_hash($password, PASSWORD_DEFAULT),
                $username
              );
              $this->redirectTo('users');
            }else {
              $this->viewUserNew(array('error' => 'Se produjo un error: el email ingresado ya existe'));
            }
          }else {
            $this->viewUserNew(array('error' => 'Se produjo un error: el nombre de usuario ingresado ya existe'));
          }
        }else {
          $this->viewUserNew(array('error' => $err));
        }
      }else {
        $this->viewUserNew(array('error' => "Se produjo un error: Faltan parámetros"));
      }
    }else {
      $this->viewLogin();
    }
  }

  //retorna un usuario a través de su id
  public function user($data = array("msg" => '', "id" => '')) {
    if($this->isLoggedUser()) { //es un usuario logueado
      $user_repo = new UserRepository();
      $user_id = isset($_POST["id"]) ? $_POST["id"] : $data["id"];
      $data['user'] = $user_repo->getUser($user_id);
      if (!empty($data)){
        $this->render('user_edit', $data);
      }else {
        $data['msg'] = 'No existe el usuario';
        $this->render('user_edit', $data);
      }
    }else {
      $this->viewLogin();
    }
  }
  
  //retorna todos los usuarios del sistema
  public function users() {
    if($this->isLoggedUser()) { //es un usuario logueado

      $user_repo = new UserRepository();
      $data = array();

      $users= $user_repo->getUsers();

      $data['users'] = $users;
      $data['loggedUserId'] = $_SESSION['id'];

      $this->render('users', $data);
    }else{
      $this->viewLogin();
    }
  }

  public function userPassChange() {
    
    $userId = $_POST["user_id"];
    $repo = new UserRepository();
    $user = $repo->getUser($userId);
    
    if (!empty($user)) {
      
      $this->prepareData(array('pass_new'));

      if ($this->postElementsCheck(array('pass_old', 'pass_new'))) {
        $passOld = $_POST['pass_old'];
        $passNew = $_POST['pass_new'];     

        if (password_verify($passOld, $user['password'])) {
          if ( ( strlen($passNew) >= 6 ) ) {
            $hashed_pass = password_hash($passNew, PASSWORD_DEFAULT);
            $repo->changePassword($userId, $hashed_pass);
            $this->user(array(
              "msg" => "La contraseña se ha modificado exitosamente.",
              "id" => $userId
              )
            );
          } else {
            $this->user(array(
              "msg" => "Error en contraseña nueva: mínimo 6 caracteres.",
              "id" => $userId
              )
            );
          }
        } else {
          $this->user(array(
            "msg" => 'La contraseña actual ingresada es incorrecta',
            "id" => $userId
            )
          );
        }
      }else {
        $this->user(array(
          'msg' => 'Se produjo un error: Faltan parámetros',
          'id' => $userId
          )
        );
      }
    }else {
      echo 'No existe ese usuario';
    }
  }

  //ejecuta la actualización de un usuario
  public function userUpdate(){
    if ($this->isLoggedUser()) {

      $userId = $_POST['id'];
      $user_repo= new UserRepository();
      $user = $user_repo->getUser($userId);

      if ($user) {
          
        $this->prepareData(array('last_name', 'first_name', 'email', 'username'));

        if ($this->postElementsCheck(array('last_name', 'first_name', 'email', 'username'))) {

          $lastName = $_POST['last_name'];
          $firstName = $_POST['first_name'];
          $email = $_POST['email'];
          $username = $_POST['username'];
        
          $err = $this->isValidForm(
            $lastName, 
            $firstName, 
            $email, 
            $username
          );
    
          $user_repo= new UserRepository();
    
          $user = $user_repo->getUser($userId);
    
          if (empty($err)) {
      
            if($user_repo->checkUserName($username, $userId)){
              if($user_repo->checkEmail($email, $userId)) {
  
                $user = $user_repo->updateUser(
                  $userId,
                  $lastName, 
                  $firstName, 
                  $email, 
                  $username
                );
  
                $this->redirectTo('users');
                
              }else {
                $this->render('user_edit',
                  array(
                    'msg' => 'Se produjo un error: el email ingresado ya existe',
                    'user' => $user
                  )
                );
              }
            }else {
              $this->render('user_edit', 
                array(
                  'msg' => 'Se produjo un error: el nombre de usuario ingresado ya existe',
                  'user' => $user
                )
              );
            }
          }else {
            $this->render('user_edit', 
              array(
                'msg' => $err,
                'user' => $user
              )
            );
          }   
        }else {
          $this->render('user_edit', 
              array(
              'msg' => 'Se produjo un error: Faltan parámetros',
              'user' => $user
            )
          );
        } 
      } else {
        $this->render('user_edit', 
          array(
            'msg' => 'Se produjo un error: No existe el usuario',
            'user' => $user
          )
        );
      }
    }else {
      $this->viewLogin();
    }
  }

  public function userDelete() {
    if($this->isLoggedUser()){ //es un usuario logueado
      if($_POST['user_id'] != $_SESSION['id']){ //el que quiere eliminar no es él mismo
        $user_repo= new UserRepository();
        $user_repo->removeUser($_POST['user_id']);
        $this->redirectTo('users');
      }else{
        $this->users();
      }
    }else {
      $this->viewLogin();
    }
  }

  public function isValidForm($last_name, $first_name, $email, $username, $password = null){
    $err='';
    if (!(preg_match("/^[a-z ñáéíóú]{2,60}+$/i", $last_name))) {
      $err.= 'error en apellido: se permiten solo letras, espacios y acentos.';
    }
    if (!(preg_match("/^[a-z ñáéíóú]{2,60}+$/i", $first_name))) {
      $err.= 'error en nombre: se permiten solo letras, espacios y acentos.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
      $err.= 'error en email: no es un email válido. ';
    }
    if ( ( strlen($username) < 6 ) || ( strlen($username) > 20 ) || (!ctype_alnum($username) ) ){
      $err.= 'error en nombre de usuario: mínimo 6 caracteres alfanuméricos, máximo 20. ';
    }
    if ($password) {
      if ( ( strlen($password) < 6 ) ) {
        $err.= 'error en password: mínimo 6 caracteres. ';
      }
    }
    return $err;
  }

}
