<?php   
namespace App\UserServices;
use App\UserServices\FieldValidation;

require_once('../vendor/autoload.php');
class ProfileUpdateService {
  /**
   * @var object
   */
  private $imageFile;

  /**
   * @var string
   */
  private string $email;

  /**
   * @var string
   */
  private string $contact;

  /**
   * @var string
   */
  private string $interest;
  
  /**
   * @var object
   */
  private object $prevDetails;

  use  FieldValidation;
  public function __construct(mixed $image, string $email, string $contact, string $interest, object $prevDetails) {
    $this->validInterests = array('pop','hiphop','romantic','dancing','');
    $this->imageFile = $image;
    $this->email = $this->trimData($email);
    $this->contact = $this->trimData($contact);
    $this->interest = $this->trimData($interest);
    $this->prevDetails = $prevDetails;
  }

  /**
   * Functuion to validate the fields while updating the profile
   * 
   * @return string
   */
  public function fieldvalidation() {
    if (!$this->validateEmail()) {
      return 'Invalid Email Formate';
    }
    elseif ($this->prevDetails->retrieveEmail() != $this->email && $this->userEmailExists()) {
      return 'User with given email already Exists';
    }
    elseif (!$this->contactValidation()) {
      return 'Invalid Contact Formate';
    }
    elseif ($this->prevDetails->retrieveContact() != $this->contact && $this->contactExists()) {
      return 'User with given contact number exists';
    }
    elseif (!$this->hasInterest()) {
      return 'Provide a Valid Interest';
    }
    elseif (!$this->picFormate()) {
      return 'Invalid Picture Formate';
    }
    else {
      return 'succcess';
    }
  }

}