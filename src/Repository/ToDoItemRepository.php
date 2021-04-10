<?php

class ToDoItemRepository extends PDORepository{

  public function getAllFinishedItems($user_id){
    $conn=$this->getConnection();
    $query= $conn->prepare("SELECT titulo, categoria.nombre AS categoria, estado.nombre AS estado FROM tarea INNER JOIN categoria ON tarea.categoria_id=categoria.id INNER JOIN estado ON tarea.estado_id=estado.id WHERE usuario_id=:user_id AND estado.nombre= 'Finalizada' ORDER BY titulo DESC");
    $query->bindParam(":user_id", $user_id);
    $query->execute();
    return $query->fetchall();
  }

  public function getCategories(){
    $conn=$this->getConnection();
    $query= $conn->prepare("SELECT * FROM categoria");
    $query->execute();
    return $query->fetchall();
  }

  public function addItem($tit, $cat_id, $user_id){
    $conn= $this->getConnection();
    $user_id= $_SESSION['id'];
    $query= $conn->prepare("INSERT INTO tarea(titulo, categoria_id, estado_id, usuario_id) VALUES(:tit, :cat_id, (SELECT id FROM estado WHERE nombre='Nueva'), :user_id)");
    $query->bindParam(":tit", $tit);
    $query->bindParam(":cat_id", $cat_id);
    $query->bindParam(":user_id", $user_id);
    $query->execute();
  }

}
