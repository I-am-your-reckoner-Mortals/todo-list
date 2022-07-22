<?php


namespace App\Services;


use App\Entity\Task;
use Doctrine\ORM\EntityRepository;

class FilterService extends AbstractFilter
{
    /**
     * @return Task[]
    */
    public function search(EntityRepository $repository, array $data, string $ordering = null): array
    {

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