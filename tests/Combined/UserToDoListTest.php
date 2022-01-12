<?php

use App\Entity\Item;
use App\Services\ToDoList;
use App\Services\User;
use App\Services\Checkers\User as CheckerUser;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserToDoList extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    /**
     * Vérifie en simulant l'envoi d'une requête que le JSON retourné correspond à ce qui est attendu lors d'un ajout
     */
    public function testControllerReturnsValidJsonOnAddViaRoute()
    {        
        $this->client->request("GET", "/user/add?email=louispelarrey@gmail.com&firstname=Louis&lastname=Pelarrey&birthdate=2001-10-12&password=azertyuiop&name[0]=X&content[0]=X&dateCreation[0]=2020-10-12&name[1]=XY&content[1]=XY&dateCreation[1]=2021-10-12");
        $data = $this->client->getResponse()->getContent();

        $expectedJson = json_encode([
            "email" => "louispelarrey@gmail.com",
            "lastname" => "Pelarrey",
            "firstname" => "Louis",
            "birthdate" => "2001-10-12",
            "password" => "azertyuiop",
            "toDoList" => [
                0 => [
                    "name" => "X",
                    "content" => "X",
                    "dateCreation" => "2020-10-12 00:00:00"
                ],
                1 => [
                    "name" => "XY",
                    "content" => "XY",
                    "dateCreation" => "2022-10-12 00:00:00"
                ]
            ]
        ]);
        
        $this->assertJsonStringEqualsJsonString($expectedJson, $data);
    }

    public function testIsValidWithGoodInfos()
    {
        $item1 = new Item(
            "Faire la vaisselle",
            "5mn à l'eau",
            "2021-10-12 00:00:00"
        );
        $item2 = new Item(
            "Faire le ménage",
            "Avec l'aspirateur",
            "2021-10-12 01:00:00"
        );

        $toDoList = new ToDoList;
        $toDoList->add($item1);
        $toDoList->add($item2);

        $user = new User(
            "louispelarrey@gmail.com",
            "Pelarrey",
            "Louis",
            date("Y-m-d", strtotime('-13 years')),
            "azertyuiop",
            $toDoList
        );

        $this->assertTrue(CheckerUser::isValid($user->getEntity()));

        $expectedToDoList = [
            0 => new Item(
                "Faire la vaisselle",
                "5mn à l'eau",
                "2021-10-12 00:00:00"
            ),
            1 => new Item(
                "Faire le ménage",
                "Avec l'aspirateur",
                "2021-10-12 01:00:00"
            )
        ];

        $this->assertEquals($expectedToDoList, $user->getEntity()->getToDoList()->getItems());
    }
}