<?php

namespace App\Controller;
use App\UserServices\UpdateProfile;
session_start();
use App\UserServices\ResetPwdService;
use App\Entity\User;
use App\UserServices\ForgotPwdService;
use App\UserServices\LoginService;
use App\UserServices\SignUpService;
use App\UserServices\SongHandler;
use App\UserServices\SongUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpParser\Node\Stmt\Catch_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
          $songs[$i]['likes'] = count($array[$i]->getLikesCounts());
          $user = $array[$i]->getAuthor();
          $songs[$i]['uName'] = $user->getUserName();
          $songs[$i]['profilePic'] = $user->getProfilePic();

        }
        return $songs;
      }

      /**
       * Function to convert an object into associativer array
       * 
       * @param object $object
       * 
       * @return array
       */
      private function objectToArray($object) {
        $song = array();
        $song['id'] = $object->getId();
        $song['songName'] = $object->getSongName();
        $song['singerName'] = $object->getSingerName();
        $song['genere'] = $object->getGenere();
        $song['thumbnail'] = $object->getThumbnail();
        $song['path'] = $object->getPath();

        return $song;
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
        return $this->render('error.html.twig');
      }

      /**
       * ROute for getting a song by its id
       */
      #[Route('/fetchSongById', name: 'fetchSongById')]
      public function fetchSongById(Request $request) {
        if ($request->isXmlHttpRequest()) {
          $songId = (int)$request->request->get('songId');
          try {
            $uId = $_SESSION['user'];
            $obj = new SongHandler($request);
            $song = $obj->getSongById($songId, $this->em);
            $userHasLiked = $obj->hasLiked($uId, $song->getId(), $this->em);
            $_SESSION['hasLiked'] = $userHasLiked;
            $_SESSION['song'] = serialize($song);
            $message = 'success';
            return $this->json([
              'message' => $message
            ]);
          }
          catch(Exception $e) {
            $message = 'Failed';
            return $this->json([
              'message' => $message
            ]);
          }
          
        }
        return $this->render('error.html.twig');

      }

      /**
       * Music Player Page
       */
      #[Route('/player', name: 'player')]
      public function player() {
        if (isset($_SESSION['login']) && isset($_SESSION['song'])) {
          $song = unserialize($_SESSION['song']);
          $songArray = $this->objectToArray($song);
          return $this->render('player.html.twig', [
            'song' => $songArray,
            'hasLiked' => $_SESSION['hasLiked']
          ]);
        }
        else {
          return $this->render('mainpage.html.twig');
        }
      }

      /**
       * Route for handling the likes
       */
      #[Route('/likesHandler', name: 'likesHandler')]
      public function likesHandler(Request $request) {
        if ($request->isXmlHttpRequest()) {
          $temp = $request->request->get('like');
          $songId = (int)$request->request->get('songId');
          $uId = $_SESSION['user'];
          $obj = new SongHandler($request);
          $message = $obj->userLikesHandler($uId, $songId, $temp, $this->em);
          if ($message == 'success') {
            if ($temp == 0) {
              $_SESSION['hasLiked'] = FALSE;
            }
            elseif ($temp == 1) {
              $_SESSION['hasLiked'] = TRUE;
            }
          }
        }
        return $this->render('error.html.twig');
      }

      /**
       * Router for the favourites page
       */
      #[Route('/favourites', name: 'favourites')]
      public function favorites() {
        if (isset($_SESSION['login'])) {
          $repository = $this->em->getRepository(User::class);
          $user = $repository->findOneBy(['id' => $_SESSION['user']]);
          return $this->render('favourites.html.twig',[
            'id' => $user->getId(),
            'fullName' => $user->getfullName(),
            'userName' => $user->getUserName(),
            'email' => $user->getEmail(),
            'picPath' => $user->getprofilePic()
          ]);
      }
      else {
        return $this->render('home.html.twig');
      }
    }
      /**
       * @Route to fetch all the songs lked by the user
       */
      #[Route('/favouritesFetcher', name: 'favouritesFetcher')]
      public function favouritesFetcher(Request $request) {
        if ($request->isXmlHttpRequest()) {
          $offset = $request->request->get('offset');
          $obj = new SongHandler($request);
          $songs = $obj->getFavourites($_SESSION['user'], $offset, $this->em);
          $array = $this->arrayBuilder($songs);
          try {
            return $this->render('Components/songs.html.twig',
            ['songs' => $array]);
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
       * Route for profile view page
       */
      #[Route('/profile', name: 'profile')]
      public function profile() {
        if (isset($_SESSION['login'])) {
          $repository = $this->em->getRepository(User::class);
          $user = $repository->findOneBy(['id' => $_SESSION['user']]);
          return $this->render('profile.html.twig',[
            'id' => $user->getId(),
            'fullName' => $user->getfullName(),
            'userName' => $user->getUserName(),
            'email' => $user->getEmail(),
            'picPath' => $user->getprofilePic(),
            'phone' => $user->getPhone(),
            'interests'=> $user->getInterests()
          ]);
        }
        else {
          return $this->render('home.html.twig');
        }
      }

      /**
       * Route for update profile page
       */
      #[Route('/update', name: 'update')]
      public function update() {
        if (isset($_SESSION['login'])) {
          $repository = $this->em->getRepository(User::class);
          $user = $repository->findOneBy(['id' => $_SESSION['user']]);
          $interests = $user->getInterests();
          $pop = '';
          $hiphop = '';
          $dancing = '';
          $romantic = '';
          $array = explode(',', $interests);

          if (in_array('pop', $array, TRUE)) {
            $pop = 'pop';
          }
          if (in_array('hiphop', $array, TRUE)) {
            $hiphop = 'hiphop';
          }
          if (in_array('dancing', $array, TRUE)) {
            $dancing = 'dancing';
          }
          if (in_array('romantic', $array, TRUE)) {
            $romantic = 'romantic';
          }
          return $this->render('update.html.twig',[
            'email' => $user->getEmail(),
            'picPath' => $user->getprofilePic(),
            'phone' => $user->getPhone(),
            'pop' => $pop,
            'hiphop' => $hiphop,
            'dancing' => $dancing,
            'romantic' => $romantic
          ]);
        }
        else {
          return $this->render('home.html.twig');
        }
      }

      /**
       * Route For the Update profile Validation
       */
      #[Route('/updateProfileValidation', name: 'updateProfileValidation')]
      public function updateProfileValidation(Request $request): Response {
        if ($request->isXmlHttpRequest()) {
          $obj = new UpdateProfile($request, $_SESSION['user']);
          $message = $obj->fieldValidation();
          if ($message == 'success') {
            $message = $obj->checkUser($this->em);
          }
          return $this->json([
            'message' => $message
          ]);
        }
        return $this->render('home.html.twig');
      }

      /**
       * Route for Logging Out
       */
      #[Route('/logout', name: 'logout')]
      public function logOut() {
        if (isset($_SESSION['login'])) {
          unset($_SESSION);
          session_destroy();
          return $this->render('home.html.twig');
        }
      }

      /**
       * Route for the Trending List Page
       */
      #[Route('/trending', name: 'trending')]
      public function trending() {
        if (isset($_SESSION['login'])) {
          $repository = $this->em->getRepository(User::class);
          $user = $repository->findOneBy(['id' => $_SESSION['user']]);
          return $this->render('trending.html.twig',
        [
            'id' => $user->getId(),
            'fullName' => $user->getfullName(),
            'userName' => $user->getUserName(),
            'email' => $user->getEmail(),
            'picPath' => $user->getprofilePic()
        ]);
        }
        return $this->render('error.html.twig');
      }

      /**
       * Route for Getting the Trending List
       * 
       *   @param Request $request
       *     Accepts an XML HTTP Request Object
       */
      #[Route('/getTrending', name: 'getTrending')]
      public function getTrending(Request $request) {
        if ($request->isXmlHttpRequest()) {
          $obj = new SongHandler($request);
          $result = $obj->getTrendingSongs(1, $this->em);
          $songs = $this->arrayBuilder($result);
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
      }
}
