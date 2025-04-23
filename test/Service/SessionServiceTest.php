<?php

namespace Salma\Belajar\PHP\MVC\Service;

use PHPUnit\Framework\TestCase;
use Salma\Belajar\PHP\MVC\Config\Database;
use Salma\Belajar\PHP\MVC\Domain\Session;
use Salma\Belajar\PHP\MVC\Domain\User;
use Salma\Belajar\PHP\MVC\Repository\SessionRepository;
use Salma\Belajar\PHP\MVC\Repository\UserRepository;

class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "salma";
        $user->name = "salma";
        $user->password = "rahasia";
        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        $session = $this->sessionService->create("salma");

        $this->expectOutputRegex("[X-SALMA-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals("salma", $result->userId);
    }

    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "salma";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-SALMA-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "salma";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);
    }
}