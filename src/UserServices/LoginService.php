<?php
namespace App\UserServices;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class LoginService {
  
  Use FieldValidation;
  /**
   * @var string $email
   */
  private string $email;

  /**
   * @var string $pwd
   */
  private string $pwd;

  /**
   * @param Request $request
   */
  public function __construct(Request $request) {
    $this->email = $this->trimData($request->request->get('email'));
    $this->pwd = stripslashes(trim($request->request->get('pwd')));
  }

  /**
   * @param EntityManagerInterface $em
   * 
   * @return string
   */
  public function checkUser(EntityManagerInterface $em): string {
    $repository = $em->getRepository(User::class);
    $emailCheck = $repository->findOneBy(['email' => $this->email]);
    if (!empty($emailCheck)) {
      if (password_verify($this->pwd, $emailCheck->getPassword())) {
        $_SESSION['user']= $emailCheck->getId();
        return 'Thank You';
      }
      else {
        return 'Wrong Password';
      }
    }
    else {
      return 'User Not Found';
    }
  }
}