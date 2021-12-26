<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToDoListTest extends WebTestCase
{
    public function testReturnsValidJsonOnAdd()
    {
        $client = static::createClient();
        $client->request('GET', '/todolist/add?name[0]=Faire+la+vaisselle&content[0]=5mn+a+froid');
        $response = $client->getResponse();
        $this->assertJsonStringEqualsJsonString(json_encode(["name" => "Faire la vaisselle", "content" => "5mn à froid"]), $response);
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
