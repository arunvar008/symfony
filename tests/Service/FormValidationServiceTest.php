<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\FormValidationService;
use Symfony\Component\Form\Forms;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Team;
use App\Util\Constant;

class FormValidationServiceTest extends WebTestCase
{

    protected function setUp()
    {
        $this->name = "test";
        $this->url = "https://er.com";
        $team = new Team();
        $team->setName($this->name);
        $team->setLogoUrl($this->url);

        $teamRepository = $this->createMock(ObjectRepository::class);
        $teamRepository->expects($this->any())
            ->method('find')
            ->willReturn($team);

        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($teamRepository);


        $this->router = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
        ->disableOriginalConstructor()
        ->getMock();
        $this->request = $this->getMockBuilder("Symfony\Component\HttpFoundation\Request")
        ->disableOriginalConstructor()
        ->getMock();
        
        $this->formFactory = Forms::createFormFactory();
        
    }

    protected function tearDown()
    {
        $this->formValidationService = null;
    }

    public function testValidateTeam()
    {
      $this->request
          ->expects($this->once())
          ->method('getContent')
          ->will($this->returnValue('{"name": "'. $this->name .'", "logo_url": "'. $this->url .'"}'));
      $this->formValidationService = new FormValidationService($this->router, $this->formFactory, $this->entityManager);
      $result = $this->formValidationService->validate(Constant::TEAM, $this->request);
      $this->assertEquals($this->name, $result->getName());
      $this->assertEquals($this->url, $result->getLogoUrl());
      $this->assertEquals(1, $result->getstatus());  
    }

    public function testValidatePlayer()
    {
      $firstName = 'firstName';
      $lastName = 'lastName';
      $teamId = '34';
      $url = "https://er.com";
      $this->request
          ->expects($this->once())
          ->method('getContent')
          ->will($this->returnValue('{"first_name": "'. $firstName .'", "last_name": "'. $lastName .'", "team_id": "'. $teamId .'", "image_url": "'. $url .'"}'));
      $this->formValidationService = new FormValidationService($this->router, $this->formFactory, $this->entityManager);
      $result = $this->formValidationService->validate(Constant::PLAYER, $this->request);
      $this->assertEquals($firstName, $result->getFirstName());
      $this->assertEquals($lastName, $result->getLastName());
      $this->assertEquals($teamId, $result->getTeamId());
      $this->assertEquals($url, $result->getImageUrl());
      $this->assertEquals(1, $result->getstatus());  
    }
}