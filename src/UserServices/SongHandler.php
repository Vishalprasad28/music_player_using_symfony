<?php

namespace App\UserServices;

use App\Entity\Posts;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;



require_once('../vendor/autoload.php');


class SongHandler {

  /**
   * @var Request
   */
  private Request $request;

  /**
   * Initializing the Request Variable
   * @param Request $request
   */
  public function __construct(Request $request) {
    $this->request = $request;
  }

  /**
   * Function to fetch the songs after certain offset
   * @param int $offset
   * @param EntityManagerInterface $em
   * 
   * @return array
   */
  public function getSongs(int $offset, EntityManagerInterface $em) {
    $repository = $em->getRepository(Posts::class);
    $songs = $repository->findBy([], ['songName' => 'ASC'], 9, $offset);
    return $songs;
  }

  /**
   * Fetching the recent song posted by the user
   * 
   * @param int $uId
   * 
   * @return array
   */
  public function getRecentSong(int $uId, EntityManagerInterface $em) {
    $repository = $em->getRepository(User::class);
    $user = $repository->findOneBy(['id' => $uId]);
    $repository = $em->getRepository(Posts::class);
    $songs = $repository->findBy(['author' => $user], ['id' => 'DESC'], 1);
    return $songs;
  }

  

}