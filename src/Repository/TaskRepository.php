<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\DTO\TaskSearch;


class TaskRepository extends EntityRepository
{
    public function findByTaskSearch(TaskSearch $dto)
    {
        $queryBuilder = $this->createQueryBuilder('ta');

        if(!empty($dto->project)){
            $queryBuilder->andWhere(
                'ta.project = :project'
            );
            $queryBuilder->setParameter('project',$dto->project);
        }
        if(!empty($dto->search)){
            $queryBuilder->andWhere(
                'ta.title like :search'
            );
            $queryBuilder->setParameter('search','%'.$dto->search.'%');
        }

        return $queryBuilder->getQuery()->execute();
    }
}