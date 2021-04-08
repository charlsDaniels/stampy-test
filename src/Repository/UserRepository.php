<?php

require_once './src/Repository/PDORepository.php';

class UserRepository extends PDORepository{

  public function login($user, $pass){
    $conn= $this->getConnection();
    $query= $conn->prepare("SELECT * FROM usuario WHERE usuario=:user AND clave=:pass");
    $query->bindParam(":user", $user);
    $query->bindParam(":pass", $pass);
    $query->execute();
    return $query->fetch();
  }

  /* Create function */
  public function newUser($lastName, $firstName, $email, $password, $username){
    $conn= $this->getConnection();
    $query= $conn->prepare("INSERT INTO user(email, username, password, created_at, updated_at, first_name, last_name)
                            VALUES(:email, :username, :password, :created_at, :updated_at, :first_name, :last_name)");
    $query->bindParam(":email", $email);
    $query->bindParam(":username", $username);
    $query->bindParam(":password", $password);
    $dateNow= date('Y-m-d H:i:s');
    $query->bindParam(":created_at", $dateNow);
    $query->bindParam(":updated_at", $dateNow);
    $query->bindParam(":first_name", $firstName);
    $query->bindParam(":last_name", $lastName);
    $query->execute();
    return $query->fetch();

  }

  public function getAllUsuarios(){
    $conn= $this->getConnection();
    $query = $conn->prepare("SELECT * FROM usuario");
    $query->execute();
    return $query->fetchall();
  } 

  function checkIfExists($query, $data, $user_id=NULL){
    $conn= $this->getConnection();
    $query= $conn->prepare($query);
    $query->bindParam(":data",$data);
    $query->execute();
    $valid = $query->fetchAll();
    if (empty($valid)){ //nadie tiene ese username
      return true;
    }
    else{//alguien tiene ese username
      if ($valid[0]['id'] == $user_id){ //es ese mismo usuario
        return true;
      }else{ //otro usuario lo tiene
        return false;
      }
    }
  }

  public function checkUserName($username, $user_id=NULL){
    $query= "SELECT * FROM user WHERE username=:data";
    return $this->checkIfExists($query, $username, $user_id);
  }

  public function checkEmail($email, $user_id=NULL){
    $query= "SELECT * FROM user WHERE email=:data";
    return $this->checkIfExists($query, $email, $user_id);
  }

}

 ?>
