<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\FormValidationService;
use Symfony\Component\Form\Forms;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\Team;
use App\Entity\Player;
use App\Util\Constant;
use App\Service\PlayerService;
use App\Repository\PlayerRepository;

class PlayerServiceTest extends WebTestCase
{

    protected function setUp()
    {
        $this->url = "https://er.com";
        $this->firstName = 'firstName';
        $this->lastName = 'lastName';
        $this->teamId = 34;

        $player = new Player();
        $player->setFirstName($this->firstName);
        $player->setLastName($this->lastName);
        $player->setImageUrl($this->url);
        $player->setTeamId($this->teamId);

        //Mock Player Repository Methods
        $playerRepository = $this->createMock(PlayerRepository::class);
        $playerRepository->expects($this->any())
            ->method('find')
            ->willReturn($player);
        $playerRepository->expects($this->any())
            ->method('savePlayer');

        //Mock Entity Manager
        $this->entityManager = $this->createMock(ObjectManager::class);
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($playerRepository);

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
          ->will($this->returnValue('{"first_name": "'. $this->firstName .'", "last_name": "'. $this->lastName .'", "team_id": "'. $this->teamId .'", "image_url": "'. $this->url .'"}'));
        
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
      $this->playerService = new PlayerService($this->router, $this->entityManager, $this->formValidationService);
      $result = $this->playerService->savePlayer($this->request);
      $this->assertTrue($result);
    }
}