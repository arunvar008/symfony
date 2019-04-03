<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Player::class);
        $this->entityManager = $entityManager;
    }

    public function savePlayer(Player $player)
    {
        $this->entityManager->persist($player);
        $this->entityManager->flush();
    }

    public function deletePlayersByTeamId(int $id)
    {
    	$qb = $this->createQueryBuilder('p');
        $query = $qb->delete('App\Entity\Player', 'pl')
            ->where('pl.team_id = :teamId')
            ->setParameter('teamId', $id)
            ->getQuery();

        $query->execute();
    }


}
