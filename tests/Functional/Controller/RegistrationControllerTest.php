<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\User\Controller\Request\UserRegistrationRequest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegistrationControllerTest extends WebTestCase
{
    private const REGISTER_URL = '/api/user/register';

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    /**
     * @throws \Exception
     */
    public function testSuccessfulRegistration(): void
    {
        $payload = new UserRegistrationRequest(
            'Jeo Jir',
            'test@mail.ru',
            '+79992828382',
            'Password123!',
            'Password123!',
        );

        $response = $this->sendRegistrationRequest($payload);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        self::assertArrayHasKey('token', $responseData);
        self::assertIsString($responseData['token']);
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $existingEmail = 'existing@mail.ru';
        $payload = new UserRegistrationRequest(
            'Vk vk',
            $existingEmail,
            '+79999999999',
            'password123',
            'password123',
        );

        $response = $this->sendRegistrationRequest($payload);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // Повторная регистрация с той же почтой
        $response = $this->sendRegistrationRequest($payload);

        $this->assertUnprocessableEntity($response);

        $content = $response->getContent();
        self::assertStringContainsString('already exists', $content);
        self::assertStringContainsString('existing@mail.ru', $content);
    }

    public function testRegistrationWithEmptyData(): void
    {
        $payload = new UserRegistrationRequest('', '', '', '', '');
        $this->assertUnprocessableEntity($this->sendRegistrationRequest($payload));
    }

    private function sendRegistrationRequest(UserRegistrationRequest $payload): Response
    {
        $this->client->request(
            Request::METHOD_POST,
            self::REGISTER_URL,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload),
        );

        return $this->client->getResponse();
    }

    private function assertUnprocessableEntity(Response $response): void
    {
        self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
}
