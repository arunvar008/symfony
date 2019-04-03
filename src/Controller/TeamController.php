<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Team controller.
 * @Route("/team", name="team")
 */
class TeamController extends FOSRestController
{

  /**
   * Lists all Teams.
   * @Rest\Get("/lists")
   *
   * @param Request $request
   *
   * @return Response
   */
  public function getTeamListAction(Request $request)
  {
    try {
      //dependency service container for team actions
      $teams = $this->get("team_service_model")->getTeamList($request);

      return $this->handleView($this->view($teams, Response::HTTP_OK));
    }
    catch(\Exception $e) {
      $statusResponse = $this->get("exception_response")->getStatusResponse($e);
      return $this->handleView($this->view(['error' => $statusResponse['statusMessage']], $statusResponse['statusCode']));
    }
  }

  /**
   * Create  a Team.
   * @Rest\Post("/add")
   *
   * @param Request $request
   *
   * @return Response
   */
  public function saveTeamAction(Request $request)
  {
    try {
      //dependency service container for team actions
      $status = $this->get("team_service_model")->saveTeam($request);
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

  /**
   * Delete a team.
   * @Rest\Delete("/{id}")
   *
   * @param Int $id
   *
   * @return Response
   */
  public function deleteTeamByIdAction($id)
  {
    try {
      //dependency service container for team actions
      $status = $this->get("team_service_model")->deleteTeamById($id);
      if ($status) {
        return $this->handleView($this->view('', Response::HTTP_NO_CONTENT));
      }
    }
    catch(\Exception $e) {
      $statusResponse = $this->get("exception_response")->getStatusResponse($e);
      return $this->handleView($this->view(['error' => $statusResponse['statusMessage']], $statusResponse['statusCode']));
    }
  }

  /**
   * Get players in a team.
   * @Rest\Get("/players/{teamId}")
   *
   * @param Int $teamId
   *
   * @return Response
   */
  public function getPlayerListByTeamIdAction($teamId)
  {
    try {
      //dependency service container for team actions
      $players = $this->get("team_service_model")->getPlayerByTeamId($teamId);
      return $this->handleView($this->view($players, Response::HTTP_OK));
    }
    catch(\Exception $e) {
      $statusResponse = $this->get("exception_response")->getStatusResponse($e);
      return $this->handleView($this->view(['error' => $statusResponse['statusMessage']], $statusResponse['statusCode']));
    }
  }
 
}