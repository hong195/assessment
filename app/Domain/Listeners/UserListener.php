<?php

namespace App\Domain\Listeners;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Model\SaleManager\SaleManager;
use App\Domain\Model\SaleManager\SaleManagerRepository;
use App\Domain\Model\User\Role;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\User\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use JetBrains\PhpStorm\NoReturn;

class UserListener
{
    private SaleManagerRepository $saleManagerRepository;
    private EntityManagerInterface $em;

    public function __construct(SaleManagerRepository $saleManagerRepository, EntityManagerInterface $em)
    {
        $this->saleManagerRepository = $saleManagerRepository;
        $this->em = $em;
    }

    /** @ORM\PostPersist */
    #[NoReturn] public function postPersistHandler(User $user, LifecycleEventArgs $event)
    {
        if (!$user->getRole()->isEqualsTo(Role::SALE_MANAGER)) {
            return;
        }
        $name = (string) $user->getFullName();
        $saleManager = new SaleManager($user->getId(), $name);
        $this->em->persist($saleManager);
        $this->em->flush();
    }
    /** @ORM\PostUpdate */
    #[NoReturn] public function postUpdateHandler(User $user, LifecycleEventArgs $event)
    {
        if (!$user->getRole()->isEqualsTo(Role::SALE_MANAGER)) {
            return;
        }
        $name = (string) $user->getFullName();
        /** @var SaleManager $saleManager */
        $saleManager = $this->saleManagerRepository->find($user->getId());
        $saleManager->changeName($name);

        $this->em->persist($saleManager);
        $this->em->flush();
    }

    /** @ORM\PreRemove */
    public function preRemoveHandler(User $user, LifecycleEventArgs $event)
    {
        if (!$user->getRole()->isEqualsTo(Role::SALE_MANAGER)) {
            return;
        }

        $saleManager = $this->saleManagerRepository->find($user->getId());

        $this->em->remove($saleManager);
        $this->em->flush();
    }
}
