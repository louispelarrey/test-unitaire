<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToDoListTest extends WebTestCase
{
    public function testReturnsValidJsonOnAdd()
    {

        $client = static::createClient();
        $client->request('GET', '');
        $response = $client->getResponse();

        $jsonBody = json_encode(["name" => "Faire la vaisselle", "content" => "5mn à froid"]);
        var_dump($jsonBody);
        $this->assertJsonStringEqualsJsonString($jsonBody, $response);
    }

    /**
    public function testIsValidWhenAddOne()
    {
        $oneAdd = $this->createTodoList([
            "name" => "Faire la vaisselle",
            "content" => "Laver à froid 5mn"
        ]);

        $toDoList = new TodoList();
        $oneItemTodoList = $toDoList->add(new Item("Faire la vaisselle", "Laver à froid 5mn"));
        $expectedOneAdd = json_encode($oneItemTodoList);

        $this->assertTrue($expectedOneAdd, $oneAdd);
    }
    */
}
