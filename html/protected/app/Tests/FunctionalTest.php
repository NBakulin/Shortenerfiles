<?php
namespace Tests;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        /** @var Connection $dbConnection */
        $dbConnection = $this->app['db'];

        // fixture

        $dbConnection->executeQuery(
            'DELETE FROM user WHERE email IN (\'test1@xsolla.com\', \'test2@xsolla.com\')'
        );

        $dbConnection->executeQuery(
            'INSERT INTO user(email, password, name) VALUES (\'test2@xsolla.com\', \'1234\', \'John Doe\')'
        );
    }


    public function createApplication()
    {
        $app = new Application();

        require __DIR__ . '/../../../../src/app.php';

        return $app;
    }

    public function testRegister()
    {
        $client = $this->createClient();
        $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], '{"email":"test1@xsolla.com", "name":"John Doe", "password":"1234"}');

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $client->request('POST', '/api/users', [], [], ['CONTENT_TYPE' => 'application/json'], '{"email":"test1@xsolla.com", "name":"John Doe", "password":"1234"}');

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $client->getResponse()->getStatusCode());
    }

    public function testGet()
    {
        $client = $this->createClient();

        $client->request('GET', '/api/users/me', [], [], ['CONTENT_TYPE' => 'application/json', 'PHP_AUTH_USER' => 'test2@xsolla.com', 'PHP_AUTH_PW' => '1234']);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testUpdate()
    {
        // lol
    }

}
