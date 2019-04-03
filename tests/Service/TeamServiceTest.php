<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\FormValidationService;
use Symfony\Component\Form\Forms;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Team;
use App\Util\Constant;
use App\Service\TeamService;
use App\Repository\TeamRepository;

class TeamServiceTest extends WebTestCase
{

    protected function setUp()
    {
        $this->name = "test";
        $this->url = "https://er.com";

        $team = new Team();
        $team->setName($this->name);
        $team->setLogoUrl($this->url);

        //Mock Team Repository Methods
        $teamRepository = $this->createMock(TeamRepository::class);
        $teamRepository->expects($this->any())
            ->method('find')
            ->willReturn($team);
        $teamRepository->expects($this->any())
            ->method('saveTeam');

        //Mock Entity Manager
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($teamRepository);

        //Mock Router
        $this->router = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
        ->disableOriginalConstructor()
        ->getMock();

        //Mock Request
        $this->request = $this->getMockBuilder("Symfony\Component\HttpFoundation\Request")
          ->disableOriginalConstructor()
          ->getMock();
        $this->request
          ->expects($this->once())
          ->method('getContent')
          ->will($this->returnValue('{"name": "'. $this->name .'", "logo_url": "'. $this->url .'"}'));
        
        $this->formFactory = Forms::createFormFactory();
        $this->formValidationService = new FormValidationService($this->router, $this->formFactory, $this->entityManager);
        
    }

    protected function tearDown()
    {
        $this->formValidationService = null;
        $this->request = null;
        $this->router = null;
        $this->formFactory = null;
        $this->entityManager = null;
    }

    public function testSaveTeam()
    {
      $this->request
          ->expects($this->once())
          ->method('getContent')
          ->will($this->returnValue('{"name": "'. $this->name .'", "logo_url": "'. $this->url .'"}'));
      $this->teamService = new TeamService($this->router, $this->entityManager, $this->formValidationService);
      $result = $this->teamService->saveTeam($this->request);
      $this->assertTrue($result);
    }
}