<?php

namespace App\Controller;
use App\UserServices\ResetPwdService;
session_start();
use App\Entity\User;
use App\Entity\Posts;
use App\UserServices\ForgotPwdService;
use App\UserServices\LoginService;
use PhpParser\Node\Scalar\MagicConst\Method;
use App\UserServices\SignUpService;
use App\UserServices\SongHandler;
use App\UserServices\SongUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

        $obj = new SignUpService($request);
        $message = $obj->fieldValidation();
        if ($message == 'success') {
          $message = $obj->checkUser($this->em);
        }
        if ($message == 'Thank You') {
          $_SESSION['login'] = TRUE;
          setcookie('login', TRUE, time() + (30), "/");
        }
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
      
      if ($request->isXmlHttpRequest()) {
        $_SESSION['email'] = $request->request->get('email');
        $user = new LoginService($request);
        $message = $user->checkUser($this->em);
        if ($message == 'Thank You') {
          $_SESSION['login'] = TRUE;
          setcookie('login', TRUE, time() + (30), "/");
        }
        return $this->json([
          'message' => $message
        ]);
      }
      return $this->render('error.html.twig');
    }

    /**
       * Redirecting to the mainpage
       */
      #[Route('/mainpage', name: 'mainpage')]
      public function mainPage(): Response {
        if (isset($_SESSION['login'])) {
          $repository = $this->em->getRepository(User::class);
          $user = $repository->findOneBy(['id' => $_SESSION['user']]);
          return $this->render('mainpage.html.twig',[
            'id' => $user->getId(),
            'fullName' => $user->getfullName(),
            'userName' => $user->getUserName(),
            'email' => $user->getEmail(),
            'picPath' => $user->getprofilePic()
          ]);
        }
        else {
          header('Refresh:0,url=/');
          return $this->render('home.html.twig');
        }
      }

      /**
       * Forgot Password Page
       */
      #[Route('/forgotPassword', name: 'forgotPassword')]
      public function forgotPassword(): Response {
        return $this->render('forgotpwd.html.twig',[
          'email' => $_SESSION['email']
        ]);
      }
      
      /**
       * Forgot Password Verification
       */
      #[Route('/forgotPwdVerificaton', name: 'forgotPasswordVerification')]
      public function forgotPasswordVerification(Request $request): Response {
        if ($request->isXmlHttpRequest()) {
          $obj = new ForgotPwdService($request);
          if ($obj->validateEmail()) {
            $message = $obj->checkUser($this->em);
            return $this->json([
              'message' => $message
            ]);
          }
          else {
            $message = 'Invalid Email Formate';
            return $this->json([
              'message' => $message
            ]);
          }
        }
        return $this->render('error.html.twig');
      }

      /**
       * Reset Password Validation
       */
      #[Route('/resetPwdValidation', name: 'resetPwdValidation')]
      public function resetPwdValidation(Request $request): Response {
        if ($request->isXmlHttpRequest()) {
          $email = $_SESSION['emailToVerify'];
          $obj = new ResetPwdService($request);
          $message = $obj->resetPwd($this->em, $email);
          
          return $this->json([
            'message' => $message
          ]);
        }
        return $this->render('error.html.twig');
      }

      /**
       * Reset Password Page
       */
      #[Route('/reset', name: 'resetPage')]
      public function reset(): Response {
        $email  = $_GET['q'];
        $email = base64_decode($email);
        $_SESSION['emailToVerify'] = $email;
        return $this->render('resetpwd.html.twig');
      }

      /**
       * Uploading the Songs
       */
      #[Route('/upload', name: 'upload')]
      public function upload(Request $request): Response {
        if ($request->isXmlHttpRequest()) {
          $user = $_SESSION['user'];
          $obj = new SongUploader($request, $user);
          $message = $obj->fieldValidation();
          if ($message == 'success') {
            if (!$obj->uploadSong($this->em)) {
              $message = 'Failed to Upload';
            }
            else {
              $message = 'Uploaded';
            }
          }
          return $this->json([
            'message' => $message
          ]);
        }
        return $this->render('error.html.twig');
      }

      /**
       * Associative array builder
       * 
       * @param array $array
       * 
       * @return array
       */
      private function arrayBuilder(array $array): array {
        $songs = array();
        for($i = 0; $i < count($array); $i++) {

          $songs[$i]['id'] = $array[$i]->getId();
          $songs[$i]['songName'] = $array[$i]->getSongName();
          $songs[$i]['singerName'] = $array[$i]->getSingerName();
          $songs[$i]['genere'] = $array[$i]->getGenere();
          $songs[$i]['thumbnail'] = $array[$i]->getThumbnail();
          $user = $array[$i]->getAuthor();
          $songs[$i]['uName'] = $user->getUserName();
          $songs[$i]['profilePic'] = $user->getProfilePic();

        }
        return $songs;
      }

      /**
       * Route for fetching the songs
       */
      #[Route('/fetchSongs', name: 'fetchSongs')]
      public function fetchSongs(Request $request): Response {
        if ($request->isXmlHttpRequest()) {
          $offset = $request->request->get('offset');
          $obj = new SongHandler($request);
          $array = $obj->getSongs($offset, $this->em);
          $songs = $this->arrayBuilder($array);
          try {
            return $this->render('Components/songs.html.twig',
            ['songs' => $songs]);
          }
          catch(Exception $e) {
            return $this->json([
              'message' => $e->getMessage()
            ]);
          }
          
        }
        return $this->render('error.html.twig');
      }

      /**
       * Route for fetching the Recent Song
       */
      #[Route('/fetchRecentSong', name: 'fetchRecentSong')]
      public function fetchRecentSong(Request $request) {
        if ($request->isXmlHttpRequest()) {
          $uId = $_SESSION['user'];
          $uId = 2;
          $obj = new SongHandler($request);
          $song = $obj->getRecentSong($uId, $this->em);
          $songs = $this->arrayBuilder($song);
          try {
            return $this->render('Components/songs.html.twig',
            ['songs' => $songs]);
          }
          catch(Exception $e) {
            return $this->json([
              'message' => $e->getMessage()
            ]);
          }
        }
        else {
        return $this->render('error.html.twig');
        }
      }
}
