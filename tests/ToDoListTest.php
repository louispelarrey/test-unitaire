<?php

namespace App\Tests;

use App\Entity\Item;
use App\Services\ToDoList;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ToDoListTest extends WebTestCase
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


    public function testDateCreationIsValid()
    {
        $item = new Item(
            "Test",
            "Random content"
        );
        $todayTimestamp = strtotime("now");
        $this->assertEquals($todayTimestamp, $item->getDateCreation()->getTimestamp());
    }

    public function testNbItems()
    {
        $todoList = new ToDoList();

        for ($i=0; $i<=10; $i++) {

            $item = new Item(
                "$i","$i",
                "202$i-01-01"
            );
            $todoList->add($item);
        }

        $this->assertEquals(count($todoList->getItems()), 10, "Le nombre de d'occurence de crétion d'item est correct");
    }

    public function testName()
    {
        $todoList = new ToDoList();
        $item1 = new Item(
            "1","1",
            "2020-01-01"
        );
        $item2 = new Item(
            "1","2",
            "2022-01-02"
        );
        $todoList->add($item1);
        $todoList->add($item2);


        $this->assertEquals(count($todoList->getItems()), 1);
    }

    public function testContent()
    {
        $todoList = new ToDoList();
        $content = "Lorem ipsum dolor sit amet. Sed voluptatem molestiae ad ullam tenetur sit internos labore est voluptas autem ea amet consequatur. Non omnis eveniet qui velit corporis sed officia totam qui velit laudantium. Qui alias delectus ea ipsa totam eum deleniti tempora. Aut delectus deleniti sed architecto galisum aut dolor quia aut ipsam voluptatem. Et dolor distinctio et perferendis quia a rerum officia qui sapiente reiciendis id laudantium saepe et quia corrupti. 33 odio laudantium sit incidunt voluptas aut blanditiis ipsam id rerum officia. In ratione corporis quas earum ad rerum obcaecati vel velit provident ea quibusdam magnam aut numquam commodi! Non facere inventore non dolores quos et molestiae neque est fugit consequuntur. Ut dolores cumque ea reiciendis quaerat et tempore nulla eos vero dolore! Qui libero quia vel quia molestias eos neque tempora qui eaque assumenda rem temporibus galisum et corrupti explicabo. Est dolorum sequi nam voluptate expedita sed odit praesentium qui quisquam amet qui minima ipsam et eveniet sunt est ratione molestiae! Et rerum galisum 33 dolorem accusantium ut iusto inventore sit officia modi sit laborum minima. Ea numquam dolore qui harum aliquid et placeat necessitatibus est iusto nisi ab cupiditate dolores. Sit quibusdam voluptate et enim vitae et voluptatem unde et consequuntur nemo quo dignissimos vitae ut corporis numquam eum autem temporibus. Ad dolore voluptas aut nobis minus et molestiae voluptas sit temporibus consequuntur non maxime voluptatum ea quod dolor. Ad nemo illum quo illo fugit est mollitia eligendi et dicta libero ut voluptatibus quia qui ducimus quibusdam. Sed officiis commodi et dolore galisum est dolor omnis id galisum officia At unde veniam. Est necessitatibus esse et animi quia id earum tempora. Qui molestiae sunt vel recusandae officia est modi perspiciatis non adipisci adipisci. Ea consequuntur Quis ut voluptates fugit ut nobis impedit qui obcaecati esse vel suscipit error. Vel iure dolor ad placeat porro vel nihil voluptates aut internos reiciendis. Et laboriosam dolorem aut impedit ducimus eos tenetur voluptas et perferendis laborum. Qui reiciendis velit est vero assumenda aut praesentium esse. Non aliquid repudiandae ea dolorem quasi aut dolorem laboriosam et placeat rerum et explicabo perferendis cum quidem rerum et sint dolorem. Est modi sequi in illum nisi et molestiae obcaecati qui nisi itaque qui itaque voluptatum ea aliquam quis et autem esse. Sit accusantium voluptas et magni odit et Quis nostrum qui quam incidunt et omnis numquam. Ad velit tempora et voluptas galisum et vitae quas eum nisi error At reprehenderit quia. 33 voluptas amet qui itaque quidem qui deserunt reiciendis et dolorum eligendi. Ut libero dolores At voluptas debitis sit reprehenderit corporis id minima iste. 33 fuga consectetur non beatae tempora sit quod fugit sed nobis omnis At autem blanditiis et repellendus quidem. Aut libero molestiae sed minus molestiae et adipisci enim eos quis facere sit veritatis ratione quo nihil laudantium. Vel nesciunt blanditiis quo neque asperiores et quaerat galisum et tempore odit sit nihil dolores in vero cupiditate. Et amet nemo aut magni similique id eveniet aliquid At odit rerum rem nesciunt quasi nulla nihil id quas sunt. Vel necessitatibus rerum et nostrum similique et maiores reprehenderit. Aut consectetur minus aut consequatur neque aut quos minus ex expedita odit ea ullam quia dolores omnis. Est fugiat dignissimos et quod laborum et repellendus debitis qui voluptatem aperiam hic deserunt cumque et nostrum repellat. Quo dolores doloribus quo voluptas similique et aperiam quia aut laborum ipsa aut quia culpa sit corrupti molestiae. Aut similique voluptas aut eligendi voluptatum qui omnis libero eum nihil laborum non aspernatur veritatis qui maiores dicta. Aut illo voluptatem ut modi incidunt et voluptates amet. Sed soluta delectus eos quia corporis nam sint ullam et nisi magnam. Id harum asperiores et velit reprehenderit eos ipsam obcaecati et natus et sint exercitationem qui deleniti obcaecati a dolorum sapiente. Ut sint quod id quod autem et molestias voluptatem! Non explicabo voluptas aut expedita consequatur sed suscipit iure sit quia accusantium ut Quis numquam qui repellendus consequatur in exercitationem excepturi? Aut tempore quam ad molestiae ducimus sed possimus odio eum sapiente eius ut similique dolores. Est aspernatur mollitia veritatis enim ut deserunt tenetur qui repellendus maxime. Est consequatur nemo est maxime consequatur sit iure voluptas et similique libero quo velit accusamus et quidem commodi eum consequatur voluptatibus. Id doloribus sint et rerum aspernatur rem quidem eligendi a error quasi ea sequi maiores. Et velit repellat ut quod nulla qui voluptates sint in vero facilis non incidunt eveniet cum quas obcaecati? Id corrupti aliquam non velit error et ratione fuga qui modi provident. Aut minus voluptas sed aperiam dicta At voluptatem quibusdam a voluptas amet quo harum temporibus et consequatur quas qui dignissimos explicabo. Aut vero veniam est esse illum in fugit quod qui amet delectus. Eos libero unde in deserunt cupiditate et minima galisum. Ut amet cumque aut obcaecati ipsa eos debitis commodi ut atque aliquid sit incidunt amet. Rem Quis modi sit harum quisquam cum labore assumenda sed galisum harum. Id obcaecati tempore non cumque dolore non dolor harum.";
        $item1 = new Item(
            "1",$content,
            "2020-01-01"
        );
        $todoList->add($item1);
        $this->assertEquals(count($todoList->getItems()), 0);
    }

}