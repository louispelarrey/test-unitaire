<?php

namespace App\Controller;

use App\Entity\Item;
use App\Services\ToDoList;
use App\Services\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Checkers\User as CheckerUser;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    private ToDoList $toDoListService;

    public function __construct(ToDoList $toDoListService)
    {
        $this->toDoListService = $toDoListService;
    }

    /**
     * Créé un User, ajoute une todolist et y ajoute X Item
     * 
     * Accessible via /user/add?email=louispelarrey@gmail.com&firstname=Louis&lastname=Pelarrey&birthdate=2001-10-12&password=azertyuiop&name[0]=X&content[0]=X&dateCreation[0]=2020-10-12&name[1]=XY&content[1]=XY&dateCreation[1]=2021-10-12
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

            $user = new User(
                $params["email"],
                $params["lastname"],
                $params["firstname"],
                $params["birthdate"],
                $params["password"],
                $this->toDoListService
            );

            if(!CheckerUser::isValid($user->getEntity())){
                throw new Exception();
            }

            return new JsonResponse($user->toArray());
        } catch (Exception $e) {
            return new JsonResponse([
                "error" => "Votre User n'a pas pu être ajouté avec les informations renseignées"
            ]);
        }
    } 
}
