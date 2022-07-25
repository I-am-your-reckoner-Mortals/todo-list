<?php


namespace App\Services;


use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

class FilterService extends AbstractFilter
{
    /**
     * @return Task[]
    */
    public function search(EntityRepository $repository, ?array $data = null, ?array $ordering = null): array
    {
        if (is_null($data)) {
            return $repository->findAll();
        }

        $searchCriteria = array_filter($data, fn ($value) => !is_null($value) && $value !== '');
        $qb = $repository->createQueryBuilder('entity');

        foreach ($searchCriteria as $name => $value) {
            if (array_key_exists($name, $this->filters)) {
                $this->filters[$name]->apply($qb, $value);
            }
        }

        return $qb->getQuery()->getResult();
    }
}