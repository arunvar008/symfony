<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Forms;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Form\PlayerForm;
use Symfony\Component\Form\FormBuilderInterface;

class playerFormTest extends WebTestCase
{
    private $systemUnderTest;

    protected function setUp()
    {
        parent::setUp();
        $this->playerForm = new PlayerForm();
    }

    /**
     * Tests that form is correctly build according to specs
     */
    public function testBuildForm(): void
    {
        $formBuilderMock = $this->createMock(FormBuilderInterface::class);
        $formBuilderMock->expects($this->exactly(6))->method('add')->willReturnSelf();
        $formBuilderMock->expects($this->exactly(6))->method('add')->withConsecutive(
          [$this->equalTo('first_name')],
          [$this->equalTo('last_name')],
          [$this->equalTo('team_id')],
          [$this->equalTo('image_url')],
          [$this->equalTo('status')],
          [$this->equalTo('save')]
        );

        $this->playerForm->buildForm($formBuilderMock, []);
    }
}
