<?php

namespace Salma\Belajar\PHP\MVC\Service;

use Salma\Belajar\PHP\MVC\Domain\Session;
use Salma\Belajar\PHP\MVC\Domain\User;
use Salma\Belajar\PHP\MVC\Repository\SessionRepository;
use Salma\Belajar\PHP\MVC\Repository\UserRepository;

function setcookie(string $name, string $value){
    echo "$name :$value";
}

class SessionService
{

    public static string $COOKIE_NAME = "X-SALMA-SESSION:";

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
    }

    public function create(string $userId): Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = $userId;
    
        $this->sessionRepository->save($session);
    
        $this->setSessionCookie($session->id);
    
        return $session;
    }
    
    private function setSessionCookie(string $sessionId): void
    {
        setcookie(self::$COOKIE_NAME, $sessionId, time() + (60 * 60 * 24 * 30), "/");
    }
    

    public function destroy()
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);

        setcookie(self::$COOKIE_NAME, '', 1 , "/");
    }

    public function current(): ?User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';

        $session = $this->sessionRepository->findById($sessionId);
        if($session == null){
            return null;
        }

        return $this->userRepository->findById($session->userId);
    }

}