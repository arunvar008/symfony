<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Player controller.
 * @Route("/player", name="player")
 */
class PlayerController extends FOSRestController
{

  /**
   * Lists all Players.
   * @Rest\Get("/lists")
   * 
   * @param Request $request
   *
   * @return Response
   */
  public function getPlayerListAction(Request $request)
  {
    try {
      //dependency service container for player actions
      $players = $this->get("player_service_model")->getPlayerList($request);

      return $this->handleView($this->view($players, Response::HTTP_OK));
    }
    catch(\Exception $e) {
      $statusResponse = $this->get("exception_response")->getStatusResponse($e);
      return $this->handleView($this->view(['error' => $statusResponse['statusMessage']], $statusResponse['statusCode']));
    }
  }

  /**
   * Create  a Player.
   * @Rest\Post("/add")
   *
   * @param Request $request
   *
   * @return Response
   */
  public function savePlayerAction(Request $request)
  {
    try {
      //dependency service container for player actions
      $status = $this->get("player_service_model")->savePlayer($request);
      if ($status === true) {
        $code = Response::HTTP_CREATED;
        return $this->handleView($this->view(['status' => Response::$statusTexts[$code]], $code));
      }
    }
    catch(\Exception $e) {
      $statusResponse = $this->get("exception_response")->getStatusResponse($e);
      return $this->handleView($this->view(['error' => $statusResponse['statusMessage']], $statusResponse['statusCode']));
    }
  }
 
}