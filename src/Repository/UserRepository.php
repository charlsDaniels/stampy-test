<?php

require_once './src/Repository/PDORepository.php';

class UserRepository extends PDORepository {
  
  public function login($user){
    $conn= $this->getConnection();
    $query= $conn->prepare("SELECT * FROM user WHERE username=:user");
    $query->bindParam(":user", $user);
    $query->execute();
    return $query->fetch();
  }

  /* Create function */
  public function newUser($lastName, $firstName, $email, $password, $username){
    $conn= $this->getConnection();
    $query= $conn->prepare(
      "INSERT INTO user(email, username, password, created_at, updated_at, first_name, last_name)
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

   /* Update functions */
  public function updateUser($userId, $lastName, $firstName, $email, $username){
    $conn= $this->getConnection();
    $query= $conn->prepare(
      "UPDATE user
        SET
          email=:email,
          username=:username,
          first_name=:first_name,
          last_name=:last_name,
          updated_at=:updated_at
        WHERE id=:user_id"
    );
    $query->bindParam(":user_id", $userId);
    $query->bindParam(":email", $email);
    $query->bindParam(":username", $username);
    $dateNow= date('Y-m-d H:i:s');
    $query->bindParam(":updated_at", $dateNow);
    $query->bindParam(":first_name", $firstName);
    $query->bindParam(":last_name", $lastName);
    $query->execute();
  }

  public function removeUser($user_id){
    $conn= $this->getConnection();
    $query= $conn->prepare("DELETE FROM user WHERE id=:id");
    $query->bindParam(":id", $user_id);
    $query->execute();
  }

  public function getUser($id){
    $conn= $this->getConnection();
    $query = $conn->prepare("SELECT * FROM user WHERE id=:id");
    $query->bindParam(":id",$id);
    $query->execute();
    return $query->fetch();
  }

  public function getUsers(){
    $conn= $this->getConnection();
    $query = $conn->prepare("SELECT * FROM user");
    $query->execute();
    return $query->fetchAll();
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
