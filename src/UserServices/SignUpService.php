<?php
namespace App\UserServices;

use App\UserServices\SignUp;

class SignUpService extends SignUp {
  
  use FieldValidation;
  /**
   * @param string $fName
   * @param string $lName
   * @param string $email
   * @param string $username
   * @param string $pwd
   * @param string $confPwd 
   */
  public function __construct(string $fName, string $lName, string $email, string $userName, string $pwd, string $confPwd, string $contact, string $interest) {
    // $this->validInterests = array('pop','hiphop','romantic','dancing','');
    $this->fName = $this->trimData($fName);
    $this->lName = $this->trimData($lName);
    $this->email = $this->trimData($email);
    $this->userName = $this->trimData($userName);
    $this->contact = $this->trimData($contact);
    $this->interest = $this->trimData($interest);
    $this->pwd = stripslashes(trim($pwd));
    $this->confPwd = stripslashes(trim($confPwd));
  }

  /**
   * Function for Signing up
   * 
   * @return string
   */
  public function signUp():string {
    $path = 'public/profile-pictures/default.png';
    if (!$this->nameValidation($this->fName) || !$this->nameValidation($this->lName)) {
      return 'Invalid Name Field formate';
    }
    elseif (!$this->validateEmail()) {
      return 'Invalid Email Formate';
    }
    elseif (!$this->contactValidation()) {
      return 'Invalid Phone Number Formate';
    }
    elseif (!$this->validatePwd()) {
      return 'Invalid Password Formate';
    }
    elseif (!$this->confpwdmatcher()) {
      return "Confirm Password Field Doesn't Match";
    }
    elseif (!$this->hasInterest()) {
      return 'Provide an Interest';
    }
    else {
      return 'success';
    }
  }
}
