<?php  
namespace App\UserServices;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ForgotPwdService{

  use FieldValidation, Mailer;
  /**
   * @var string $email
   */
  private string $email;
  
  /**
   * Constructor for initialization
   * 
   * @param Request $request
   */
  public function __construct(Request $request) {
    $this->email = $this->trimData($request->request->get('email'));
  }
  
  /**
   * Function to check if the user exists
   * 
   * @return string
   */
  public function checkUser(EntityManagerInterface $em): string {
    $repository = $em->getRepository(User::class);
    $emailCheck = $repository->findOneBy(['email' => $this->email]);

    if (!empty($emailCheck)) {
      $subject = 'Reset Psassword';
      $body = '<h3 style="font-weight:bold;">Your Reset Password Link is ther link will expire in 10 minutes</h3><a href="example.com/reset?q='. base64_encode($this->email) .'">Reset Your Password</a>';
      if ($this->sendMail($subject, $body)) {
        return 'We Have Sent You a reset Password Link';
      }
      else {
        return 'Failed To Verify';
      }
    }
    else {
      return 'User Not Found';
    }

  }
}