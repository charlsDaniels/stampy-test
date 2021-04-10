<?php

require_once './model/ToDoItemRepository.php';

class ToDoItemController extends MainController{

  public function viewTodoList(){
    if(isset($_SESSION['id'])){//es un usuario logueado
      $repo= new ToDoItemRepository();
      $items = $repo->getAllFinishedItems($_SESSION['id']);
      $params= array('items'=>$items);
      self::$twig->show('view_todo_list.html', $params);
    }
  }

  public function viewAddToDoItem(){
    if(isset($_SESSION['id'])){//es un usuario logueado
      $repo= new ToDoItemRepository();
      $params=array();
      $categorias= $repo->getCategories();
      $params['categorias']= $categorias;
      self::$twig->show('view_add_todo_item.html', $params);
    }
  }

  public function addToDoItem(){
    if(isset($_SESSION['id'])){//es un usuario logueado
      if(isset($_POST['titulo']) && isset($_POST['cat_id'])){
        if(!empty($_POST['titulo']) && !empty($_POST['cat_id'])){
          $repo= new ToDoItemRepository();
          $repo->addItem($_POST['titulo'], $_POST['cat_id'], $_SESSION['id']);
          $this->redirectHome();
        }
      }
    }
  }

}
