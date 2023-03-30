<?php 
namespace App\UserServices;
class ActiveUser {

  /**
   * @var string $fullName;
   *   Stores the Full Name of the User.
   */
  private string $fullName;

  /**
   * @var int $id
   */
  private int $id;

  /**
   * @var string $userName;
   *   Stores the UserName of the User.
   */
  private string $userName;

  /**
   * @var string $email;
   *   Stores the Email Address of the User.
   */
  private string $email;

  /**
   * @var int $status;
   *   Stores the status of the User whether he/she is online or Offline.
   */
  public int $status;

  /**
   * @var string
   */
  private string $picturePath;
  
  /**
   * @var string
   */
  private string $contact;

  /**
   * @var string
   */
  private string $interests;

  /**
   * Constructor for ActiveUser
   */
  public function __construct(string $fullName, string $email, string $userName, string $contact, string $interests, string $picPath, int $id) {
    $this->fullName = $fullName;
    $this->email = $email;
    $this->userName = $userName;
    $this->contact = $contact;
    $this->interests = $interests;
    $this->picturePath = $picPath;
    $this->id = $id;
  }
  
  /**
   * @param string
   */
  public function updateEmail($email) {
    $this->email = $email;
  }

  /**
  * @param string
  */
  public function updateContact($contact) {
    $this->contact = $contact;
  }

    /**
   * @param string
   */
  public function updateInterests($interest) {
    $this->interests = $interest;
  }

    /**
   * @param string
   */
  public function updatePicPath($path) {
    $this->picturePath = $path;
  }

  /**
   * @return string
   */
  public function retrieveContact() {
    return $this->contact;
  }

    /**
   * @return string
   */
  public function retrieveInterests() {
    return $this->interests;
  }
  /**
   * @return string;
   */
  public function retrieveFullName() {
    return $this->fullName;
  }

  /**
   * @return string;
   */
  public function retrieveEmail() {
    return $this->email;
  }

  
  /**
   * @return string;
   */
  public function retrieveUserName() {
    return $this->userName;
  }


  /**
   * @return integer;
   */
  public function retrieveId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function retrievePicturePath() {
    return $this->picturePath;
  }
  
}
