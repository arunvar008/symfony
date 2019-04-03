<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use App\Entity\Team;
use App\Entity\Player;
use App\Util\Constant;
use App\Util\Exceptions;

/*
 * service class for team manipulation
 */
class TeamService
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
   * post team method to store team data
   *
   * @param Request $request
   *
   * @return Boolean \ Exceptions
   */
  public function saveTeam(Request $request)
  {
    $team = $this->formValidationContainer->validate(Constant::TEAM, $request);

    if (is_array($team) && $team['errors']) {
      throw new Exceptions\FormInvalidException(json_encode($team['errors']));
    } else {
      $this->entityManager->getRepository(Team::class)->saveTeam($team);
      return true;
    }

  }

  /*
   * get team method to get team list
   *
   * @param Request $request
   *
   * @return App\Entity\Team
   */
  public function getTeamList(Request $request) : Array
  {
    $teams = $this->entityManager->getRepository(Team::class)->findAll();

    return $teams;
  }

  /*
   * delete team method to delte team 
   *
   * @param Int $id
   *
   * @return Boolean \ Exceptions
   */
  public function deleteTeamById($id)
  {
    $team = $this->getTeamStatusByTeamId($id);
    if (!$team) {
      throw new Exceptions\ResourceNotFoundException($id);
    }

    $em = $this->entityManager;
    $em->getRepository(Player::class)->deletePlayersByTeamId($id);
    $em->getRepository(Team::class)->removeTeam($team);

    return true;
  }

  /*
   * get team player method to get players list from team
   *
   * @param Int $teamId
   *
   * @return App\Entity\Player
   */
  public function getPlayerByTeamId($teamId)
  {
    $status = $this->getTeamStatusByTeamId($teamId);
    if (!$status) {
      throw new Exceptions\ResourceNotFoundException($teamId);
    }

    $em = $this->entityManager;  
    $players = $em->getRepository(Player::class)->findBy(
        ['team_id' => $teamId]
    );

    return $players;
  }

  /*
   * get team status method to check team exists or not
   *
   * @param Int $teamId
   *
   * @return App\Entity\Team
   */
  public function getTeamStatusByTeamId($teamId)
  {
    $em = $this->entityManager;
    $team = $em->getRepository(Team::class)->find($teamId);

    if (!$team) {
      return false;
    }

    return $team;
  }
 
}