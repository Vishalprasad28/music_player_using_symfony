<?php

namespace App\UserServices;

use App\Entity\LikesCount;
use App\Entity\Posts;
use App\Entity\User;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;



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
   * @param EntityManagerInterface $em
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

  /**
   * Fetching a song from  its id
   * 
   * @param int $uId
   * @param EntityManagerInterface $em
   * 
   * @return object
   */
  public function getSongById(int $songId, EntityManagerInterface $em): object {
    $repository = $em->getRepository(Posts::class);
    $song = $repository->findOneBy(['id' => $songId]);
    return $song;
  }

  /**
   * User Likes Handler
   * 
   * @param int $uId
   * @param int $songId
   * @param int $temp
   * @param EntityManagerInterface $em
   * 
   * @return string
   */
  public function userLikesHandler(int $uId, int $songId, int $temp, EntityManagerInterface $em): string {
    $postRepo = $em->getRepository(Posts::class);
    $userRepo = $em->getRepository(User::class);
    $user = $userRepo->findOneBy(['id' => $uId]);
    $song = $postRepo->findOneBy(['id' => $songId]);
    $likesRepo = $em->getRepository(LikesCount::class);
    $userHasLiked = $likesRepo->findOneBy(['song' => $song, 'likedBy' => $user]);
    if ($temp == 1) {
      if (empty($userHasLiked)) {
        try {
          $like = new LikesCount();
          $like->setSong($song);
          $like->setLikedBy($user);
          $em->persist($like);
          $em->flush();
          return 'success';
        }
        catch(Exception $e) {
          return $e;
        }
      }
      else {
        return 'success';
      }
    }
    elseif ($temp == 0) {
      if (!empty($userHasLiked)) {
        try {
          $em->remove($userHasLiked);
          $em->flush();
          return 'success';
        }
        catch(Exception $e) {
          return $e;
        }
      }
      else {
        return 'success';
      }
    }
    else {
      return 'Failed';
    }
  }

  /**
   * Function to check idf the user has liked a perticular post
   * @param int $uId
   * @param int $songId
   * @param EntityManagerInterface $em
   * 
   * @return bool
   */
  public function hasLiked(int $uId, int $songId, EntityManagerInterface $em) {
    $postRepo = $em->getRepository(Posts::class);
    $userRepo = $em->getRepository(User::class);
    $user = $userRepo->findOneBy(['id' => $uId]);
    $song = $postRepo->findOneBy(['id' => $songId]);
    $likesRepo = $em->getRepository(LikesCount::class);
    $userHasLiked = $likesRepo->findOneBy(['song' => $song, 'likedBy' => $user]);
    if (!empty($userHasLiked)) {
      return TRUE;
    }
    else {
      return FAlSE;
    }
  }

  /**
   * Function to get all songs the user has liked
   * 
   * @param int $uId
   * @param int $offset
   * @param EntityManagerInterface $em
   * 
   * @return array
   */
  public function getFavourites(int $uId, int $offset, EntityManagerInterface $em): array {
    $userRepo = $em->getRepository(User::class);
    $songRepo = $em->getRepository(Posts::class);
    $user = $userRepo->findOneBy(['id' => $_SESSION['user']]);
    $likesRepo = $em->getRepository(LikesCount::class);
    $likes = $likesRepo->findBy(['likedBy' => $_SESSION['user']], [], 9, $offset);
    $songs = array();
    foreach($likes as $like) {
      $song = $songRepo->findOneBy(['id' => $like->getSong()]);
      array_push($songs, $song);
    }
    return $songs;
  }

  /**
   * Function to fetch all the trending songs
   * Songs that has like count above a certain number
   * 
   *   @param int $likeCount
   *     Accepts the like count for a trending song
   * 
   *   @param EntityManagerInterface $em
   *     Accepts the Entity Manager to handle the entity classes
   * 
   *   @return array
   *     Returns the array of the songs that are trending
   */
  public function getTrendingSongs(int $likeCount, EntityManagerInterface $em) {
    $likesRepo = $em->getRepository(LikesCount::class);
    $songRepo = $em->getRepository(Posts::class);
    $offset = $this->request->get('offset');
    
    $songIds = $likesRepo->findByTrending($likeCount, $offset);
    $songs = array();
    foreach ($songIds as $songId) {
      $song = $songRepo->findOneBy(['id' => $songId['id']]);
      array_push($songs, $song);
    }
    return $songs;
  }
}
