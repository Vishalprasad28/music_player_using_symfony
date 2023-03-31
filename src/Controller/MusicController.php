<?php

namespace App\Controller;

use App\Entity\User;
use PhpParser\Node\Scalar\MagicConst\Method;
use App\UserServices\SignUpService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends AbstractController
{

  /**
   * @var mixed
   */
    private $em;

    public function __construct(EntityManagerInterface $em) {
      $this->em = $em;
    }

    /**
     * Redirecting to the home page
     */
    #[Route('/', name: 'home')]
    public function home():Response
    {
      return $this->render('home.html.twig');
    }

    /**
     * Redirecting to the Sign Up Page
     */
    #[Route('/register', name: 'register')]
    public function register():Response
    {
      return $this->render('signup.html.twig');
    }

      /**
     * Redirecting to the Login Page
     */
    #[Route('/login', name: 'login')]
    public function login():Response
    {
      return $this->render('login.html.twig');
    }
    /**
     *validating after Signup 
     */
    #[Route('/postSignUp', name: 'postSignUp')]
    public function postSignUp(Request $request):Response
    {
      if ($request->isXmlHttpRequest()) {
        //signup vallidation will go here;
        // $fName = $request->request->get('fname');
        // $lName = $request->request->get('lname');
        // $email = $request->request->get('email');
        // $phone = $request->request->get('phone');
        // $uname = $request->request->get('uname');
        // $pwd = $request->request->get('pwd');
        // $confPwd = $request->request->get('confPwd');
        // $pop = $request->request->get('pop');
        // $hiphop = $request->request->get('hiphop');
        // $romantic = $request->request->get('romantic');
        // $dancing = $request->request->get('dancing');
        // $interest = "";
        // if (isset($pop)) {
        //   $interest = $interest . 'pop,';
        // }
        // if (isset($hiphop)) {
        //   $interest = $interest . 'hiphop,';
        // }
        // if (isset($romantic)) {
        //   $interest = $interest . 'romantic,';
        // }
        // if (isset($dancing)) {
        //   $interest = $interest . 'dancing,';
        // }

        $obj = new SignUpService($request);
        $message = $obj->fieldValidation();
        if ($message == 'success') {
          $message = $obj->checkUser($this->em);
        }

        return $this->json([
          'message' => $message
        ]);
        // $fName = $obj->trimData($fName);
        // $lName = $obj->trimData($lName);
        // $email = $obj->trimData($email);
        // $uname = $obj->trimData($uname);
        // $phone = $obj->trimData($phone);
        // $interest = $obj->trimData($interest);

        // $obj->setter($fName, $lName, $email, $uname, $pwd, $confPwd, $phone, $interest);
        // $message = $obj->fieldValidation();

        // if ($message == 'success') {
        //   $repository = $this->em->getRepository(User::class);
        //   $emailCheck = $repository->findOneBy(['email' => $email]);
        //   $phoneCheck = $repository->findOneBy(['phone' => $phone]);
        //   $userNameCheck = $repository->findOneBy(['userName' => $uname]);
        //   if (!empty($emailCheck)) {
        //     return $this->json([
        //       'message' => 'Email Already Exists'
        //     ]);
        //   }
        //   elseif (!empty($phoneCheck)) {
        //     $message = 'Phone Number Already Exists';
        //   }
        //   elseif (!empty($userNameCheck)) {
        //     $message = 'User Name Already Exists';
        //   }
        //   else {
        //     $user = new User();

        //     $user->setUserName($uname);
        //     $user->setFullName($fName . $lName);
        //     $user->setEmail($email);
        //     $user->setPhone($phone);
        //     $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        //     $user->setPassword($hashedPwd);
        //     $user->setInterests($interest);
            
        //     $this->em->persist($user);
        //     $this->em->flush();

        //     return $this->json([
        //       'message' => 'Thank You'
        //     ]);
        //   }
        // }
        // return $this->json([
        //   'message' => $message
        // ]);
        }  
        return $this->render('error.html.twig');
      }

      /**
       * Redirecting to the mainpage
       */
    #[Route('/mainpage', name: 'mainpage')]
      public function mainPage(): Response {
        return $this->render('mainpage.html.twig');
      }

      /**
     *validating after Login
     */
    #[Route('/postLogin', name: 'postLogin')]
    public function postLogin(Request $request): Response
    {
      
      if ($request->isXmlHttpRequest()) {

      }
      return $this->render('error.html.twig');
    }
}   