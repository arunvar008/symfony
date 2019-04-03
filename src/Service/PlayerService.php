<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use App\Entity\Player;
use App\Entity\Team;
use App\Util\Constant;
use App\Util\Exceptions;


/*
 * Player Service
 */
class PlayerService
{
  private $router;
  private $entityManager;
  private $formValidationContainer;

  public function __construct($router, $entityManager, $formValidationContainer)
  {
      $this->router = $router;
      $this->entityManager = $entityManager;
      $this->formValidationContainer = $formValidationContainer;
  }

  /*
   * post player method to store player data
   *
   * @param Request $request
   *
   * @return Boolean \ Exceptions
   */
  public function savePlayer(Request $request)
  {
      $teamId = $request->get('team_id');
      $team = $this->entityManager->getRepository(Team::class)->find($teamId);
      if (!$team) {
        throw new Exceptions\ResourceNotFoundException($teamId); 
      }

      $player = $this->formValidationContainer->validate(Constant::PLAYER, $request);

      if (is_array($player) && $player['errors']) {
        throw new Exceptions\FormInvalidException(json_encode($player['errors']));
      } else {
        $this->entityManager->getRepository(Player::class)->savePlayer($player);
        return true;
      }
  }

  /*
   * get player list method to get player data
   *
   * @param Request $request
   *
   * @return App\Entity\Team
   */
  public function getPlayerList(Request $request) : Array
  {
    $players = $this->entityManager->getRepository(Player::class)->findAll();

    return $players;
  }
 
}