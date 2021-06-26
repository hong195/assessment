<?php


namespace App\Infrastructure\Persistence\InMemory;

use Doctrine\Common\Collections\ArrayCollection;
use App\Exceptions\DomainException;
use App\Domain\Model\User\Login;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    private ArrayCollection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection([]);
    }

    public function getAll(): ArrayCollection
    {
        return $this->users;
    }
    /**
     * @param UserId $userId
     * @return User|null
     */
    public function findById(UserId $userId): ?User
    {
        $collection = $this->users->filter(function ($user) use ($userId) {
            if ($userId->isEqual($user->getId())) {
                return $user;
            }
            return null;
        });

        return $collection->isEmpty() ? null : $collection->first();
    }

    /**
     * @param UserId $userId
     * @return User|null
     * @throws DomainException
     */
    public function findOrFail($id) : User
    {
        $found = $this->findById(new UserId($id));

        if (!$found) {
            throw new DomainException('User Not Found');
        }

        return $found;
    }

    /**
     * @param User $user
     * @throws DomainException
     */
    public function add(User $user) : void
    {
        /** @var User $aUser */
        foreach ($this->users as $aUser) {
            if ($user->getId()->isEqual($aUser->getId())) {
                throw new DomainException('User with this uuid is already added!');
            }
        }

        $this->users->add($user);
    }

    public function remove(User $user) : void
    {
        foreach ($this->users as $key => $user2) {
            if ($user2->getId()->isEqual($user->getId())) {
                unset($this->users[$key]);
                break;
            }
        }
    }

    public function findByIds(array $ids): ArrayCollection
    {
        return $this->users->filter(function ($user)  use ($ids){
            foreach ($ids as $id) {
                if ($user->getId()->isEqual($id)) {
                    return $user;
                }
            }
            return null;
        });
    }

    public function findByLogin(Login $login): ?User
    {
        $found =  $this->users->filter(function ($user)  use ($login){
            if ((string) $user->getLogin() === (string) $login) {
                return $user;
            }
            return null;
        });

        return $found->first() ?? null;
    }
}
