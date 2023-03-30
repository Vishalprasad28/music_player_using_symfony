<?php 
namespace App\UserServices;
use App\UserServices\FieldValidation;

class SongUploadService {

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
   * @param string $songName
   * @param string singer
   * @param string $genere
   * @param object $song
   */
  public function __construct(string $songName, string $singer, string $genere, mixed $song, mixed $profilePic, int $uId) {
    // $this->email = $this->trimData($email) ;
    $this->songName = $this->trimData($songName);
    $this->singer = $this->trimData($singer);
    $this->genere = $genere;
    $this->song = $song;
    $this->imageFile = $profilePic;
    $this->uId = $uId;
  }

  /**
   * Field validation method
   * 
   * @return string
   */
  public function fieldValidation() {
    if(!$this->nameValidation($this->singer)) {
      return 'Invalid Singer name Formate';
    }
    else if(!$this->audioFormate()) {
      return 'Invalid Audio Formate';
    }
    elseif (!$this->thumbnailFormate()) {
      return 'Invalid Image Formate';
    }
    else if(empty($this->genere)) {
      return 'please provide genere';
    }
    else {
      return 'success';
    }
  }
}
