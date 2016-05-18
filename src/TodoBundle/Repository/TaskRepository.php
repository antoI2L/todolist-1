<?php

namespace TodoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TodoBundle\Entity\Tag;
use TodoBundle\Entity\User;

class TaskRepository extends EntityRepository
{
    private function getTasksBetweenDates($minDate, $maxDate, $user)
    {
        return $this->createQueryBuilder('t')
            ->where('t.dueDate >= :minDate')->setParameter('minDate', $minDate)
            ->andWhere('t.dueDate <= :maxDate')->setParameter('maxDate', $maxDate)
            ->andWhere('t.user = :user')->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getAllOfTheDay(User $user)
    {
        $minDate = new \DateTime();
        $minDate->format('Y-m-d H:i:s');
        $minDate->setTime(0, 0, 0);

        $maxDate = new \DateTime();
        $maxDate->format('Y-m-d H:i:s');
        $maxDate->setTime(23, 59, 59);

        return $this->getTasksBetweenDates($minDate, $maxDate, $user);
    }

    public function getAllOfTheWeek(User $user)
    {
        return $this->getTasksBetweenDates(
            date('Y-m-d', strtotime('monday this week')),
            date('Y-m-d 23:59:59', strtotime('sunday this week')),
            $user
        );
    }

    public function getAllOfTheMonth(User $user)
    {
        return $this->getTasksBetweenDates(
            date('Y-m-01'),
            date('Y-m-t'),
            $user
        );
    }

    public function getTasksByCategoryAndUser(User $user, $category, $field, $order)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')->setParameter('user', $user)
            ->andWhere('t.category = :category')->setParameter('category', $category)
            ->orderBy('t.'.$field, $order)
            ->getQuery()
            ->getResult();
    }

    public function getTasksByTagAndUser(User $user, Tag $tag, $field, $order)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.tag', 'tag')
            ->where('tag = :tag')->setParameter('tag', $tag)
            ->andWhere('t.user = :user')->setParameter('user', $user)
            ->orderBy('t.'.$field, $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param string $field Le champ sur lequel on va trier
     * @param string $order asc ou desc
     */
    public function getTasksOrdered(User $user, $field, $order)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')->setParameter('user', $user)
            ->orderBy('t.'.$field, $order)
            ->getQuery()
            ->getResult();
    }

    public function getOutdatedTasks(User $user)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.dueDate < :now')
            ->setParameter('user', $user)
            ->setParameter('now', date('Y-m-d H:i'))
            ->orderBy('t.dueDate', 'asc')
            ->getQuery()
            ->getResult();
    }
}
