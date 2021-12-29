<?php

namespace App\Controller;

use App\Entity\Item;
use App\Services\ToDoList;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todolist', name: 'todolist_')]
class ToDoListController extends AbstractController
{
    private ToDoList $toDoListService;

    public function __construct(ToDoList $toDoListService)
    {
        $this->toDoListService = $toDoListService;
    }

    /**
     * Créé une ToDoList et y ajoute X Item
     * 
     * Accessible via /todolist/add?name[0]=X&content[0]=X&dateCreation[0]=2020-10-12&name[1]=XY&content[1]=XY&dateCreation[1]=2021-10-12
     * @param GET/POST name
     * @param GET/POST content
     * @param GET/POST dateCreation
     */
    #[Route('/add', name: 'create', methods: ['GET', 'POST'])]
    public function add(Request $request): JsonResponse
    {
        try {
            $params = $request->query->all();
            for ($i = 0; $i < count($params["name"]); $i++) {
                $item = new Item(
                    $params["name"][$i],
                    $params["content"][$i],
                    $params["dateCreation"][$i]
                );
                $this->toDoListService->add($item);
            }
            return new JsonResponse($this->toDoListService->getItems());
        } catch (Exception $e) {
            return new JsonResponse([
                "error" => "Votre item n'a pas pu être ajouté à la ToDoList"
            ]);
        }
    }
}
