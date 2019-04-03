<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use App\Entity\Team;
use App\Entity\Player;
use App\Form\TeamForm;
use App\Form\PlayerForm;
use App\Util\Constant;

/*
 * Form Validation Test Commonly for all classes
 */
class FormValidationService
{
  private $router;
  private $formFactory;
  private $entityManager;

  public function __construct($router, $formFactory, $entityManager)
  {
      $this->router = $router;
      $this->formFactory = $formFactory;
      $this->entityManager = $entityManager;
  }

  public function createForm(string $type, $data = null, array $options = array())
  {
      return $this->formFactory->create($type, $data, $options);
  }

  /*
   * validate method for validating a form
   * 
   */
  public function validate(string $type, Request $request)
  {
    $data = json_decode($request->getContent(), true);
    switch ($type) {
      case Constant::TEAM:
          $object = new Team();
          $class = TeamForm::class;
          break;
      case Constant::PLAYER:
          $object = new Player();
          $class = PlayerForm::class;
          break;
    }

    $form = $this->createForm($class, $object);
    $data['status'] = 1;
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      return $object;
    } else {
      $errors = array();
      foreach($form->getErrors() as $error)
      {
        $errors[] = $error->getMessage();
      }
      return ['errors' => $errors];
    }
  }
 
}