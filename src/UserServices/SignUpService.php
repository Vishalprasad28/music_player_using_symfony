<?php
namespace App\UserServices;

use App\Entity\User;
use App\UserServices\SignUp;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class SignUpService extends SignUp {
  
  use FieldValidation, Mailer;

  /**
   * COnstructor that acepts the request variable
   * 
   * @param Request $request
   */
  public function __construct(Request $request) {
    $this->validInterests = array('pop','hiphop','romantic','dancing','');
    $this->fName = $this->trimData($request->request->get('fname')) ;
    $this->lName = $this->trimData($request->request->get('lname'));
    $this->email = $this->trimData($request->request->get('email'));
    $this->phone = $this->trimdata($request->request->get('phone'));
    $this->userName = $this->trimdata($request->request->get('uname'));
    $this->pwd = stripslashes(trim($request->request->get('pwd')));
    $this->confPwd = stripslashes(trim($request->request->get('confPwd')));
    $pop = $request->request->get('pop');
    $hiphop = $request->request->get('hiphop');
    $romantic = $request->request->get('romantic');
    $dancing = $request->request->get('dancing');
    $this->interest = "";

    if (isset($pop)) {
      $this->interest = $this->interest . 'pop,';
    }
    if (isset($hiphop)) {
      $this->interest = $this->interest . 'hiphop,';
    }
    if (isset($romantic)) {
      $this->interest = $this->interest . 'romantic,';
    }
    if (isset($dancing)) {
      $this->interest = $this->interest . 'dancing,';
    }
  }
  
   /**
   * Function for Signing up
   * 
   * @return string
   */
  public function fieldValidation():string {
    
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

  /**
   * Checking if the user exists in the  database
   * 
   * @param EntityManagerInterface $em
   * 
   * @return string
   */
  public function checkUser(EntityManagerInterface $em): string {
    $repository = $em->getRepository(User::class);
    $emailCheck = $repository->findOneBy(['email' => $this->email]);
    $phoneCheck = $repository->findOneBy(['phone' => $this->phone]);
    $userNameCheck = $repository->findOneBy(['userName' => $this->userName]);
    if (!empty($emailCheck)) {
      $message = 'Email Already Exists';
    }
    elseif (!empty($phoneCheck)) {
      $message = 'Phone Number Already Exists';
    }
    elseif (!empty($userNameCheck)) {
      $message = 'User Name Already Exists';
    }
    else {
      $message = $this->register($em);
    }
    return $message;
  }

  /**
   * Registring the user
   * 
   * @param EntityManagerInterface $em
   */
  private function register(EntityManagerInterface $em) {
    $user = new User();
    try {
      $user->setUserName($this->userName);
      $user->setFullName($this->fName . ' ' . $this->lName);
      $user->setEmail($this->email);
      $user->setPhone($this->phone);
      $hashedPwd = password_hash($this->pwd, PASSWORD_DEFAULT);
      $user->setPassword($hashedPwd);
      $user->setInterests($this->interest);
      $em->persist($user);
      $em->flush();
      $_SESSION['user']= serialize($user);
      $sub = 'Radiohead.co.in Greets';
      $body ='Thank You for joining us';
      $this->sendMail($sub, $body);
      $message = 'Thank You';
    }
    catch (Exception $e) {
      $message = 'Failed';
    }
    return $message;
  }
}
