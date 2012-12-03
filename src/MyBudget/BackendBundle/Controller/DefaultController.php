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

        $oldest = $em->getRepository('EntryBundle:Entry')->getOldest();
        $newest = $em->getRepository('EntryBundle:Entry')->getNewest();
        $balance = $em->getRepository('EntryBundle:Entry')->getBalance();
        $balance_class = ($balance < 0)? 'negative' : 'positive';
        
        return $this->render('BackendBundle:Default:index.html.twig', array(
        	'since' => $oldest->getDateEntry(),
        	'until' => $newest->getDateEntry(),
        	'balance' => $balance,
            'balance_class' => $balance_class
    	));
    }
}
