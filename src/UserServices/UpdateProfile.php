<?php

namespace App\UserServices;

use Exception;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UpdateProfile {

  use FieldValidation, Mailer;

  /**
   * @var string $email
   */
  private string $email;

  /**
   * @var string $phone
   */
  private string $phone;

  /**
   * @var string $interests
   */
  private string $interest;

  /**
   * @var object $imageFile
   */
  private $imageFile;
  
  /**
   * @var int $uId
   */
  private int $uId;

  /**
   * @var string $randomPicName
   */
  private string $randomPicName;

  /**
   * Constructor for initializing the fields
   * @param Request $request
   * @param int $uId
   */
  public function __construct(Request $request, int $uId) {

    $this->validInterests = array('pop', 'hiphop', 'romantic', 'dancing', '');
    $this->email = $this->trimData($request->request->get('email'));
    $this->phone = $this->trimData($request->request->get('phone'));
    $this->imageFile = $request->files->get('profile');
    $this->uId = $uId;

    $pop = $request->request->get('pop');
    $hiphop = $request->request->get('hiphop');
    $romantic = $request->request->get('romantic');
    $dancing = $request->request->get('dancing');
    $this->interest = '';

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
   * Validating the Fields
   * 
   * 
   * @return string
   */
  public function fieldValidation(): string {
    if (!$this->validateEmail()) {
      return 'Invalid Emal Formate';
    }
    elseif (!$this->contactValidation()) {
      return 'Invalid Phone Number Formate';
    }
    elseif (!$this->hasInterest()) {
      return 'Provide a Valid Interest';
    }
    elseif (!$this->picFormate()) {
      return 'Invalid Picture Formate';
    }
    else {
      return 'success';
    }
  }

  /**
   * Checking if the user with given credentials already exists
   * 
   * @param EntityManagerInterface $em
   * 
   * @return string
   */
  public function checkUser(EntityManagerInterface $em): string {
    $userRepo = $em->getRepository(User::class);
    $oldCredentials = $userRepo->findOneBy(['id' => $this->uId]);
    $oldEmail = $oldCredentials->getEmail();
    $oldPhone = $oldCredentials->getPhone();

    if (!empty($emailCheck) && $this->email != $oldEmail) {
      return 'Email Already Exixts';
    }
    elseif (!empty($phoneCheck) && $this->phone != $oldPhone) {
      return 'Phone Number Already Exists';
    }
    elseif (!$this->updateCredentials($em)) {
      return "Couldn't Update";
    }
    else {
      return 'Updated';
    }
  }

  /**
   * Update Credentials of User
   * 
   * @param EntityManagerInterface $em
   * 
   * @return bool
   */
  private function updateCredentials(EntityManagerInterface $em) {
    $userRepo = $em->getRepository(User::class);
    try {
      $picPath = 'profilePic/' . $this->randomPicName;
      $oldCredentials = $userRepo->findOneBy(['id' => $this->uId]);
      $oldCredentials->setEmail($this->email);
      $oldCredentials->setPhone($this->phone);
      $oldCredentials->setInterests($this->interest);
      $oldCredentials->setProfilePic($picPath);
      $em->merge($oldCredentials);
      $em->flush();
      return TRUE;
    }
    catch(Exception $e) {
      return FALSE;
    }
    

  }
}
