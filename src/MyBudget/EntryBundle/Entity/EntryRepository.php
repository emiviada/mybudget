<?php

namespace MyBudget\EntryBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EntryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntryRepository extends EntityRepository
{
	/*
	 * getBalance() method
	 * @desc Returns the final balance for the given period
	 *       (full period by default)
	 * @params DateTime $from, DateTime $to
	 * @return float $balance
	 */
	public function getBalance(DateTime $from = null, DateTime $to = null)
	{
		$q = $this->getEntityManager()
            ->createQuery('SELECT SUM(CASE e.haber WHEN 1 THEN e.value ELSE e.value * (-1) END) AS balance FROM EntryBundle:Entry e');
            
        $result = $q->getResult();

        return (count($result))? $result[0]['balance'] : null;
	}

	/*
	 * getFromTo() method
	 * @desc Returns all the entries from $from to $to
	 * @params DateTime $from, DateTime $to
	 * @return array collection $entries
	 */
	public function getFromTo(\DateTime $from = null, \DateTime $to = null)
	{
		$q = $this->createQueryBuilder('e');
		
		if (!is_null($from))
			$q->where('e.date_entry >= :from');

		if (!is_null($to) && !is_null($from))
			$q->andWhere('e.date_entry <= :to');
		elseif (!is_null($to) && is_null($from))
			$q->where('e.date_entry <= :to');

		$q->setParameters(array(
			'from' => $from,
			'to' => $to
		));

		$q = $q->getQuery();
            
        $entries = $q->getResult();

        return (count($entries))? $entries : null;
	}

	/*
	 * getOldest() method
	 * @desc Returns the oldest Entry object
	 * @param
	 * @Returns Entry $entry
	 */
	public function getOldest()
	{
		$q = $this->getEntityManager()
				->getRepository('EntryBundle:Entry')
				->createQueryBuilder('e')
				->orderBy('e.date_entry', 'ASC')
				->setMaxResults(1)
				->getQuery();

		$entry = $q->getSingleResult();

		return $entry;
	}

	/*
	 * getNewest() method
	 * @desc Returns the newest Entry object
	 * @param
	 * @Returns Entry $entry
	 */
	public function getNewest()
	{
		$q = $this->getEntityManager()
				->getRepository('EntryBundle:Entry')
				->createQueryBuilder('e')
				->orderBy('e.date_entry', 'DESC')
				->setMaxResults(1)
				->getQuery();

		$entry = $q->getSingleResult();

		return $entry;
	}
}
