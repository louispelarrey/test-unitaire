<?php

namespace App\Tests;

Use App\Entity\Item;
use App\Services\EmailSenderService;
use App\Services\ToDoList;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToDoListTest extends WebTestCase
{
    private KernelBrowser $client;
    private EmailSenderService $emailSenderService;

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->emailSenderService = $this->getMockBuilder(EmailSenderService::class)
            ->onlyMethods(['sendEmail'])
            ->getMock();

        parent::setUp();
    }

    /**
     * Vérifie en simulant l'envoi d'une requête que le JSON retourné correspond à ce qui est attendu lors d'un ajout
     */
    public function testReturnsValidJsonOnAddViaRoute()
    {        
        $this->client->request("GET", "/todolist/add?name[0]=Faire+la+vaisselle&content[0]=5mn+a+froid&dateCreation[0]=2020-10-12");
        $data = $this->client->getResponse()->getContent();

        $expectedJson = json_encode([
            0 => [
                "name" => "Faire la vaisselle",
                "content" => "5mn a froid",
                "dateCreation" => "2020-10-12 00:00:00"
            ]
        ]);
        
        $this->assertJsonStringEqualsJsonString($expectedJson, $data);
    }

    /**
     * Vérifie que l'API retourne bien un message d'erreur lors de l'envoi d'un mauvais paramètre (badParam[0])
     */
    public function testReturnsErrorMessageOnAddViaRouteWithBadParam()
    {
        $this->client->request('GET', '/todolist/add?name[0]=Faire+la+vaisselle&badParam[0]=5mn+a+froid&dateCreation[0]=2020-10-12');
        $data = $this->client->getResponse()->getContent();

        $expectedJson = json_encode([
            "error" => "Votre item n'a pas pu être ajouté à la ToDoList"
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $data);
    }

    /**
     * Vérifie que l'API retourne un Item dans la todolist lorsque l'on utilise le même name pour deux items
     */
    public function testDoesntAddTwoItemsWhenSameName()
    {
        $this->client->request('GET', '/todolist/add?' . 
            'name[0]=Faire+la+vaisselle'.
            '&content[0]=5mn+a+froid'.
            '&dateCreation[0]=2020-10-12 00:00:00'.

            '&name[1]=Faire+la+vaisselle'.
            '&content[1]=Description+differente'.
            '&dateCreation[1]=2020-10-12'
        );
        $data = $this->client->getResponse()->getContent();

        $expectedJson = json_encode([
            0 => [
                "name" => "Faire la vaisselle",
                "content" => "5mn a froid",
                "dateCreation" => "2020-10-12 00:00:00"
            ]
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $data);
    }

    /**
     * Vérifie que l'API retourne un Item dans la todolist lorsque l'on utilise le même name pour deux items
     */ 
    public function testNotWorkingWithLongerThan1000CharactersContent()
    {
        $thousandAndOneCharacterString = $this->generateRandomString();
        $this->client->request('GET', '/todolist/add?' . 
            "name[0]=Faire+la+vaisselle".
            "&content[0]={$thousandAndOneCharacterString}".
            '&dateCreation[0]=2020-10-12 00:00:00'
        );
        $data = $this->client->getResponse()->getContent();

        $expectedJson = json_encode([
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $data);
    }

    /**
     *  Vérifie que l'API envoie un mail à l'utilisateur lorsque le 8ème item est ajouté
     */
    public function testSendMailWhenAddingEighthItem()
    {
        $toDoListService = new ToDoList();
        for ($i = 0; $i < 8; $i++) {
            $item = new Item(
                $i, $i,
                "202$i-01-01"
            );
            $toDoListService->add($item);
        }
        $this->emailSenderService->expects($this->any())
            ->method('sendEmail')
            ->willReturn(true);

        $this->assertTrue($toDoListService->checkEighthAdd($this->emailSenderService));
    }

    /**
     *  Vérifie que l'API n'envoie pas de mail à l'utilisateur lorsque le 7ème item est ajouté
     */
    public function testSendMailWhenAddingSeventhItem()
    {
        $toDoListService = new ToDoList($this->emailSenderService);
        for ($i = 0; $i < 7; $i++) {
            $item = new Item(
                $i, $i,
                "202$i-01-01"
            );
            $toDoListService->add($item);
        }
        $this->emailSenderService->expects($this->any())
            ->method('sendEmail')
            ->willReturn(true);

        $this->assertFalse($toDoListService->checkEighthAdd($this->emailSenderService));
    }

    /**
     * Génère une string random pour les tests
     */
    private function generateRandomString(int $length = 1001): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}