<?php
namespace App\UserServices;

abstract class SignUp {

  /**
   * @var string
   */
  protected string $fName;

  /**
   * @var string
   */
  protected string $lName;

  /**
   * @var string
   */
  protected $fullName;

  /**
   * @var string $userName;
   *   Stores the User Id of the User.
   */
  protected $userName;

  /**
   * @var string $email;
   *   Stores the Email Address of the User.
   */
  protected string $email;

  /**
   * @var string $pwd
   *   Stores the password of the User.
   */
  protected string $pwd;

  /**
   * @var string
   */
  protected string $confPwd;

  /**
   * @var string
   */
  protected string $contact;

  /**
   * @var string
   */
  protected string $interest;

  /**
   * @var int $status;
   *   Stores the status of the User whether he/she is online or Offline.
   */
  public int $status;

  /**
   * Function that sign ups the user
   * 
   */
  abstract function signUp();

}

