<?php

require_once './src/Repository/UserRepository.php';

class UserController extends MainController {

  public function viewLogin($params = array("error" => '')) {
    $this->render('login', $params);
  }

  public function login() {
    // if(!$this->isLoggedUser()) { //no tiene ya una sesión iniciada
      if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(!empty($_POST['username']) && !empty($_POST['password'])){
          $repo = new UserRepository();
          $user = $repo->login($username, $password);
          if(!empty($user)) {
            $this->startUserSession($user);
            $_GET['action'] = ''; 
            $this->dispatch('users');
          }else{
            $this->viewLogin(array("error" => 'Usuario o contraseña incorrectos'));
          }
        }else {
          $this->viewLogin(array("error" => 'Faltó completar alguno de los datos'));
        }
      }else {
        $this->viewLogin(array("error" => 'Faltó completar alguno de los datos'));
      }
    // }
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
    $this->dispatch('login');
  }

  public function viewUserNew($params = array("error" => '')) {
    $this->render('user_new', $params);
  }

  public function userNew() {

    if($this->isLoggedUser()) { //es un usuario logueado

      $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
      $firstName =  isset($_POST['first_name']) ? $_POST['first_name'] : '';
      $email = isset($_POST['email']) ? $_POST['email'] : '';
      $password = isset($_POST['password']) ? $_POST['password'] : '';
      $username = isset($_POST['username']) ? $_POST['username'] : '';

      $err = $this->isValidForm(
        $lastName, 
        $firstName, 
        $email, 
        $password, 
        $username
      );

      if(empty($err)){
          $user_repo= new UserRepository();
          if($user_repo->checkUserName($username)){
            if($user_repo->checkEmail($email)) {
              $user = $user_repo->newUser(
                $lastName, 
                $firstName, 
                $email, 
                $password, 
                $username
              );
              $this->dispatch('users');
            }else {
              $this->viewUserNew(array('error' => 'Se produjo un error: el email ingresado ya existe'));
            }
          }else {
            $this->viewUserNew(array('error' => 'Se produjo un error: el nombre de usuario ingresado ya existe'));
          }
      }else {
        $this->viewUserNew(array('error' => $err));
      }    
    }

  }

  public function user($data = array("error" => '')) {
    if($this->isLoggedUser()) { //es un usuario logueado
      $user_repo = new UserRepository();
      $user_id = $_POST["id"];
      $data['user'] = $user_repo->getUser($user_id);
      if (!empty($data)){
        $this->render('user_edit', $data);
      }else {
        $data['error'] = 'No existe el usuario';
        $this->render('user_edit', $data);
      }
    }else {
      $this->viewLogin();
    }
  }

  public function users($state=NULL, $msg="") {
    if($this->isLoggedUser()) { //es un usuario logueado

      $user_repo = new UserRepository();
      $data = array();

      if(!is_null($state)){
        $data[$state]= $msg;
      }

      $users= $user_repo->getUsers();
      $data['users'] = $users;

      $this->render('users', $data);
    }else{
      $this->viewLogin();
    }
  }

  public function userUpdate(){
    if ($this->isLoggedUser()) { 
      $userId = isset($_POST['id']) ? $_POST['id'] : '';
      $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
      $firstName =  isset($_POST['first_name']) ? $_POST['first_name'] : '';
      $email = isset($_POST['email']) ? $_POST['email'] : '';
      $password = isset($_POST['password']) ? $_POST['password'] : '';
      $username = isset($_POST['username']) ? $_POST['username'] : '';

      $err = $this->isValidForm(
        $lastName, 
        $firstName, 
        $email, 
        $password, 
        $username
      );

      $user_repo= new UserRepository();

      $user = $user_repo->getUser($userId);

      if (empty($err)) {

        if ($user) {

          if($user_repo->checkUserName($username, $userId)){
            if($user_repo->checkEmail($email, $userId)) {

              $user = $user_repo->updateUser(
                $userId,
                $lastName, 
                $firstName, 
                $email, 
                $password, 
                $username
              );

              $this->dispatch('users');
              
            }else {
              $this->render('user_edit',
                array(
                  'error' => 'Se produjo un error: el email ingresado ya existe',
                  'user' => $user
                )
              );
            }
          }else {
            $this->render('user_edit', 
              array(
                'error' => 'Se produjo un error: el nombre de usuario ingresado ya existe',
                'user' => $user
              )
            );
          }
        }else {
          $this->render('user_edit', 
            array(
              'error' => 'Se produjo un error: No existe el usuario',
              'user' => $user
            )
          );
        }   
      }else {
        $this->render('user_edit', 
          array(
            'error' => $err,
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
      if($_POST['id_user'] != $_SESSION['id']){ //el que quiere eliminar no es él mismo
        $user_repo= new UserRepository();
        $user_repo->removeUser($_POST['id_user']);
        
        // $this->viewUsersList('success', 'El usuario fue eliminado');
      }else{
        echo('Se produjo un error: no puedes eliminar a ese usuario');
        // $this->viewUsersList('error', 'Se produjo un error: no puedes eliminar a ese usuario');
      }
    }else {
      $this->viewLogin();
    }

  }

  public function postElementsCheck($elements){
    $i = 0;
    $i_max = count($elements);
    $ok = ($i < $i_max);
    while($ok && $i < $i_max){
      $key = $elements[$i++];
      $ok = (isset($_POST[$key]) && (!empty($_POST[$key]) || is_numeric($_POST[$key])));
    }
    return $ok;
  }

  public function isValidForm($last_name, $first_name, $email, $password, $username){
    $err='';
    if ($this->postElementsCheck(array('last_name','first_name','email','password','username'))) {
      $this->prepareData(array('last_name', 'first_name', 'email', 'password', 'username'));
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
      if ( ( strlen($password) < 6 ) || ( strlen($password) > 20 ) ) {
        $err.= 'error en password: mínimo 8 caracteres, máximo 20. ';
      }
    }else {
      $err.= 'Se produjo un error: faltó completar alguno/s de los datos. ';
    }
    return $err;
  }

  public function prepareData($elements) {
    foreach($elements as $key){
      if(isset($_POST[$key])){
        $_POST[$key] = trim($_POST[$key]); //Elimina espacio en blanco (u otro tipo de caracteres) del inicio y el final de la cadena
        $_POST[$key] = strip_tags($_POST[$key]); //Retira las etiquetas HTML y PHP de un string
        $_POST[$key] = addslashes($_POST[$key]); //Escapa un string con barras invertidas
        $_POST[$key] = stripslashes($_POST[$key]); //Quita las barras de un string con comillas escapadas
        $_POST[$key] = htmlspecialchars($_POST[$key]); //Convierte caracteres especiales en entidades HTML
      }
    }
  }

}
