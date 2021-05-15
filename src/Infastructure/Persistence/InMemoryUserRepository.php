<?php


namespace Infastructure\Persistence;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Exceptions\DomainException;
use Domain\Model\User\User;
use Domain\Model\User\UserId;
use Domain\Model\User\UserRepository;

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
    public function findOrFail(UserId $userId) : User
    {
        $found = $this->findById($userId);

        if (!$found) {
            throw new DomainException('User Not Found');
        }

        return $found;
    }

    public function add(User $user)
    {
        foreach ($this->users as $aUser) {
            if ($user->getId()->isEqual($aUser->getId())) {
                throw new DomainException('User with this uuid is already added!');
            }
        }

        $this->users->add($user);
    }

    public function remove(UserId $userId) : void
    {
        foreach ($this->users as $key => $user) {
            if ($userId->isEqual($user->getId())) {
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
}
