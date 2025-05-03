<?php

namespace Functional\Controller;

use App\Dto\UserRegistrationDto;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    private const REGISTER_URL = '/api/register';

    /**
     * @throws \Exception
     */
    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();

        $payload = new UserRegistrationDto(
            'Jeo Jir',
            'test@mail.ru',
            '+79992828382',
            'Password123!',
            'Password123!'
        );

        $client->request(
            Request::METHOD_POST,
            self::REGISTER_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertIsString($responseData['token']);
    }
}
