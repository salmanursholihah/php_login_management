<?php

namespace Salma\Belajar\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use Salma\Belajar\PHP\MVC\Config\Database;
use Salma\Belajar\PHP\MVC\Domain\User;

class UserRepositoryTest extends TestCase
{

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = "salma";
        $user->name = "salma";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById("notfound");
        self::assertNull($user);
    }

}