<?php

namespace App\UserServices;

use App\Entity\Posts;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;



require_once('../vendor/autoload.php');


class SongUploader
{

  /**
   * @var string
   */
  private string $songName;

  /**
   * @var string
   */
  private string $singer;

  /**
   * @var object
   */
  private $imageFile;

  /**
   * @var string
   */
  private string $randomSongName;

  /**
   * @var string
   */
  private string $randomPicName;

  /**
   * @var object
   */
  private $song;

  /**
   * @var string
   */
  private string $genere;

  /**
   * @var int
   */
  private int $uId;

  /**
   * Including the required traits
   */
  use FieldValidation;
  /**
   * Constructor for initialization
   * @param Request $request
   *
   * @param int $uId
   */
  public function __construct(Request $request, int $uId)
  {
    $this->randomPicName = '';
    $this->songName = $this->trimData($request->get('name'));
    $this->singer = $this->trimdata($request->get('singer'));
    $this->genere = "";
    $this->song = $request->files->get('song');
    $this->imageFile = $request->files->get('profile');
    $this->uId = $uId;
    $pop = $request->request->get('pop');
    $hiphop = $request->request->get('hiphop');
    $romantic = $request->request->get('romantic');
    $dancing = $request->request->get('dancing');
    if (isset($pop)) {
      $this->genere = $this->genere . 'pop,';
    }
    if (isset($hiphop)) {
      $this->genere = $this->genere . 'hiphop,';
    }
    if (isset($romantic)) {
      $this->genere = $this->genere . 'romantic,';
    }
    if (isset($dancing)) {
      $this->genere = $this->genere . 'dancing,';
    }
  }

  /**
   * Field validation method
   * 
   * @return string
   */
  public function fieldValidation()
  {
    if (!$this->nameValidation($this->singer)) {
      return 'Invalid Singer name Formate';
    }
    else if (!$this->audioFormate()) {
      return 'Invalid Audio Formate';
    }
    elseif (!$this->thumbnailFormate()) {
      return 'Invalid Image Formate';
    } 
    else if (empty($this->genere)) {
      return 'please provide genere';
    } 
    else {
      return 'success';
    }
  }

  /**
   * Uploading Song data to the Database
   * 
   * @param EntityManagerInterface $em
   * 
   * @return string
   */
  public function uploadSong(EntityManagerInterface $em) {
    $song = new Posts();
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['id' => $this->uId]);
    try {
      $songPath = 'songs/' . $this->randomSongName;
      if ($this->randomPicName != '') {
        $cover = 'cover/' . $this->randomPicName;
      }
      else {
        $cover = 'cover/defaultmusic.jpg';
      }
      $song->setAuthor($user);
      $song->setSongName($this->songName);
      $song->setSingerName($this->singer);
      $song->setGenere($this->genere);
      $song->setPath($songPath);
      $song->setThumbnail($cover);
      $em->persist($song);
      $em->flush();
      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

}
