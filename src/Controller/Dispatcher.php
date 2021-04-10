<?php

require_once './src/Controller/MainController.php';
require_once './src/Controller/UserController.php';

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

  static function view_user_new(){
    UserController::getInstance()->viewUserNew();
  }

  static function user_new(){
    UserController::getInstance()->userNew();
  }

  static function user_edit(){
    UserController::getInstance()->user();
  }

  static function users(){
    UserController::getInstance()->users();
  }

  static function user_update(){
    UserController::getInstance()->userUpdate();
  }

  static function user_delete(){
    UserController::getInstance()->userDelete();
  }

}
