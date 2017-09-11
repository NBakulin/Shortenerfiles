<?php
namespace Tests;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $connection = $this->app['db'];

        // fixture

        $connection->executeQuery(
            'DELETE FROM user WHERE email IN (\'test1@xsolla.com\', \'test2@xsolla.com\')'
        );

        $connection->executeQuery(
            'INSERT INTO user(userid, email, login , name ,password) VALUES (\'test2@xsolla.com\', \'1234\', \'John Doe\')'
        );
    }


    public function createApplication()
    {
        $app = new Application();

        require __DIR__ . '/../../../public/index.php';

        return $app;
    }

    public function testCreateUser()
    {
        $client = $this->CreateUser();
        $client->request('POST', '/users', [], [], ['CONTENT_TYPE' => 'application/json'], '{"email":"test1@xsolla.com", "login":"ShlikovIsTheBest","name":"IShakirovToo", "password":"1234"}');

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $client->request('GET', '/users/me', [], [],['CONTENT_TYPE' => 'application/json', 'PHP_AUTH_USER' => 'ShlikovIsTheBest', 'PHP_AUTH_PW' => '1234']);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
    }


    public function testUpdate()
    {
        // lol
    }

}
