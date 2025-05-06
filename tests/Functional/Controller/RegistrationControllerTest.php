<?php

namespace Functional\Controller;

use App\Dto\UserRegistrationDto;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    private const REGISTER_URL = '/api/register';
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws \Exception
     */
    public function testSuccessfulRegistration(): void
    {
        $payload = new UserRegistrationDto(
            'Jeo Jir',
            'test@mail.ru',
            '+79992828382',
            'Password123!',
            'Password123!'
        );

        $response = $this->sendRegistrationRequest($payload);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertIsString($responseData['token']);
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $existingEmail = 'existing@mail.ru';
        $payload = new UserRegistrationDto(
            'Vk vk',
            $existingEmail,
            '+79999999999',
            'password123',
            'password123'
        );

        $response = $this->sendRegistrationRequest($payload);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // Повторная регистрация с той же почтой
        $response = $this->sendRegistrationRequest($payload);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $content = $response->getContent();
        $this->assertStringContainsString('already exists', $content);
        $this->assertStringContainsString('existing@mail.ru', $content);
    }

    public function testRegistrationWithEmptyData(): void
    {
        $payload = new UserRegistrationDto('', '', '', '', '');
        $response = $this->sendRegistrationRequest($payload);
        $this->assertUnprocessableEntity($response);
    }

    public function testRegistrationWithInvalidEmail(): void
    {
        $payload = new UserRegistrationDto(
            'Federico Kik',
            'invalid-email',
            '+79999999',
            'password123',
            'password123'
        );
        $response = $this->sendRegistrationRequest($payload);
        $this->assertUnprocessableEntity($response);
    }

    public function testRegistrationWithWeakPassword(): void
    {
        $payload = new UserRegistrationDto(
            'Kejr Uwww',
            'weakPassword@mail.ru',
            '+799988986',
            '123',
            '123'
        );
        $response = $this->sendRegistrationRequest($payload);
        $this->assertUnprocessableEntity($response);
    }

    public function testRegistrationWithMismatchedPasswords(): void
    {
        $payload = new UserRegistrationDto(
            'Luna Pw',
            'passwordMismatch@mail.ru',
            '+79999999',
            'password123!',
            'password123'
        );
        $response = $this->sendRegistrationRequest($payload);
        $this->assertUnprocessableEntity($response);
    }

    private function sendRegistrationRequest(UserRegistrationDto $payload): Response
    {
        $this->client->request(
            Request::METHOD_POST,
            self::REGISTER_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        return $this->client->getResponse();
    }

    private function assertUnprocessableEntity(Response $response): void
    {
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
}
