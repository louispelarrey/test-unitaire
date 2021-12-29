
## Projet Test Unitaire

Karim Dahoumane
Julien Arbellini
Louis Pelarrey

## Requirements

  ⚠️⚠️⚠️⚠️⚠️⚠️PHP8⚠️⚠️⚠️⚠️⚠️⚠️

-  [Docker](https://www.docker.com/)

-  [Docker Compose](https://docs.docker.com/compose/)


  

## Usage

  
### Dependencies
```
$ docker-compose -d --build
```

  

### Test

  Aller dans le container php-fpm (ou si php 8 installé sur l'ordinateur directement dessus)

```console
$ ./vendor/phpunit/phpunit/phpunit -c phpunit.xml --testdox -v
```

Pour créer une ToDoList, il faudra utiliser l'URL type ci-dessous :

http://localhost/todolist/add?name[0]=X&content[0]=X&dateCreation[0]=2002-10-10&name[1]=XY&content[1]=S&dateCreation[1]=2002-10-10+02:39

Pour créer un User contenant une ToDoList, il faudra utiliser l'URL ci-dessous:

http://localhost/user/add?email=louispelarrey@gmail.com&firstname=Louis&lastname=Pelarrey&birthdate=2001-10-12&password=azertyuiop&name[0]=X&content[0]=X&dateCreation[0]=2020-10-12&name[1]=XY&content[1]=XY&dateCreation[1]=2021-10-12
  

Pour ajouter un nouvel objet il suffit de reprendre les URL précédentes et d'y ajouter :

&name[1]=XY&content[1]=S&dateCreation[1]=2002-10-10+02:39

**Attention :**

- Ajouter deux items du même nom est impossible (sera retenu seul le premier item contenant le nom en question, les autres items de la ToDoList seront bien sûr conservés.

- Ajouter deux items dont la différence de date de création est 30min sera impossible. Encore une fois, si cette contrainte n'est pas respectée l'item ne sera pas pris en compte et ne sera donc pas ajouté à la liste

- Un User ne peut qu'avoir une seule ToDoList par essence étant donné la manière d'implémentation

Nous avons fait le choix de ne pas utiliser de BDD pour ne pas complexifier le projet, cependant il serait possible d'en implémenter une pour avoir une persistance des données (voir de créer un compte utilisateur etc...)