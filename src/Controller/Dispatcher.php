<?php

require_once './src/Controller/MainController.php';
require_once './src/Controller/UserController.php';
// require_once './src/Controller/ToDoItemController.php';

class Dispatcher{

  static function home(){
    MainController::getInstance()->viewHome();
  }

  static function view_login(){
    UserController::getInstance()->viewLogin();
  }

  static function login(){
    UserController::getInstance()->login();
  }

  static function logout(){
    UserController::getInstance()->logout();
  }

  static function register(){
    UserController::getInstance()->register();
  }

  static function user(){
    UserController::getInstance()->user();
  }

  static function users(){
    UserController::getInstance()->users();
  }

  static function userUpdate(){
    UserController::getInstance()->userUpdate();
  }

  static function userDelete(){
    UserController::getInstance()->userDelete();
  }

  

  // static function view_todo_list(){
  //   ToDoItemController::getInstance()->viewTodoList();
  // }

  // static function view_add_todo_item(){
  //   ToDoItemController::getInstance()->viewAddToDoItem();
  // }

  // static function add_todo_item(){
  //   ToDoItemController::getInstance()->addToDoItem();
  // }

}
