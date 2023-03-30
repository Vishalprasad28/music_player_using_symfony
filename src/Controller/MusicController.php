<?php

namespace App\Controller;

use PhpParser\Node\Scalar\MagicConst\Method;
use App\UserServices\SignUpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends AbstractController
{
    // #[Route('/{name}', name: 'app_music', defaults: ['name' => 'Vishal'], methods: ['GET', 'HEAD'])]
    // public function index($name):Response
    // {
    //   return $this->render('index.html.twig', [
    //     'songName' => $name
    //   ]);
    // }
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
        $fName = $request->request->get('fname');
        $lName = $request->request->get('lname');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
        $uname = $request->request->get('uname');
        $pwd = $request->request->get('pwd');
        $confPwd = $request->request->get('confPwd');
        $pop = $request->request->get('pop');
        $hiphop = $request->request->get('hiphop');
        $romantic = $request->request->get('romantic');
        $dancing = $request->request->get('dancing');
        $interest = "";
        if (isset($pop)) {
          $interest = $interest . 'pop,';
        }
        if (isset($hiphop)) {
          $interest = $interest . 'hiphop,';
        }
        if (isset($romantic)) {
          $interest = $interest . 'romantic,';
        }
        if (isset($dancing)) {
          $interest = $interest . 'dancing,';
        }

        $obj = new SignUpService($fName, $lName, $email, $uname, $pwd, $confPwd, $phone, $interest);
        $message = $obj->signUp();
        return $this->json([
          'message' => $message
        ]);
      }
      return $this->render('error.html.twig');

    }

   /**
     *validating after Login
     */
    #[Route('/postLogin', name: 'postLogin')]
    public function postLogin(Request $request): Response
    {
      $email = 'email';
      $pwd = 'password';
      if ($request->isXmlHttpRequest()) {
        //login vallidation will go here;
        return $this->json([
        'message' => 'we are on the Login Validation',
        'email' => $email,
        'pwd' => $pwd,
        'request' => $request
        ]);
      }
      return $this->render('error.html.twig');
    }
}
