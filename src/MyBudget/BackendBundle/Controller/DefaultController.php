<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    /*
     * index() action (Dashboard)
     * @desc Renders the dashboard
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entryRepository = $em->getRepository('EntryBundle:Entry');

        $oldest = $entryRepository->getOldest();
        $newest = $entryRepository->getNewest();
        $balance = $entryRepository->getBalance();
        $balance_class = ($balance < 0)? 'negative' : 'positive';

        $today = new \DateTime();
        $aux = new \DateTime();
        $prevMonth = $aux->modify('-1 month');
        $entries = $entryRepository->getFromTo(
            new \DateTime($prevMonth->format('Y-m-01')),
            new \DateTime($today->format('Y-m-t'))
        );
        
        $ing = $out = array(
            'current' => 0,
            'previous' => 0,
        );
        if (count($entries)) {
            foreach ($entries as $entry) {
                $key = ($entry->getDateEntry()->format('m') == $today->format('m'))? 'current' : 'previous';
                if ($entry->getHaber())
                    $ing[$key] += $entry->getValue();
                else
                    $out[$key] += $entry->getValue();
            }
        }
        
        return $this->render('BackendBundle:Default:index.html.twig', array(
        	'since' => $oldest->getDateEntry(),
        	'until' => $newest->getDateEntry(),
        	'balance' => $balance,
            'balance_class' => $balance_class,
            'today' => $today,
            'prevMonth' => $prevMonth,
            'ing' => $ing,
            'out' => $out
    	));
    }
}
