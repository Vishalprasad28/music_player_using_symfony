<?php  
namespace App\UserServices;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ResetPwdService{

  use FieldValidation;
  /**
   * @var string $email
   */
  private string $pwd;

   /**
   * @var string $email
   */
  private string $confPwd;
  
  /**
   * Constructor for initialization
   * 
   * @param Request $request
   */
  public function __construct(Request $request) {
    $this->pwd = stripslashes(trim($request->request->get('pwd')));
    $this->confPwd = stripslashes(trim($request->request->get('confPwd')));
  }
  
  /**
   * Function to check if the user exists
   * 
   * @param EntityManagerInterface $em
   * 
   * @return string
   */
  public function resetPwd(EntityManagerInterface $em, string $email): string {
    $repository = $em->getRepository(User::class);
    $emailCheck = $repository->findOneBy(['email' => $email]);

    if (!empty($emailCheck)) {
      if (!$this->validatePwd()) {
        return 'Invalid Password Formate';
      }
      elseif (!$this->confpwdmatcher()) {
        return "Confirm Password Field Doesn't Match";
      }
      else {
        try{
          $hashedPwd = password_hash($this->pwd, PASSWORD_DEFAULT);
          $emailCheck->setPassword($hashedPwd);
          $em->merge($emailCheck);
          $em->flush();
          return 'success';
        }
        catch(Exception $e) {
          return 'Failed';
        }
        
      }
    }
    else {
      return 'User Not Found';
    }

  }
}