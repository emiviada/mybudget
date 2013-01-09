<?php

namespace MyBudget\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $response = new Response("<h1>MyBudget API</h1>");
        return $response;
    }

    /*
     * getEntries Controller
     * @desc Get the entries (Could be filtered)
     * @return JSON
     */
    public function getEntriesAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
    	
        $from = ($request->query->has('from'))? new \DateTime($request->query->get('from')) : null;
        $to = ($request->query->has('to'))? new \DateTime($request->query->get('to')) : null;

        $response = new Response();
        
    	$entries = $em->getRepository('EntryBundle:Entry')->getFromTo($from, $to);
    	$aEntries = array();
    	$count = count($entries);
    	if ($count) {
    		foreach ($entries as $entry) {
    			$aEntries[] = $entry->toArray();
    		}
    	}
    	$statusCode = 200;

    	$result['status'] = $statusCode;
    	$result['message'] = "Success";
    	$result['count'] = $count;
    	$result['results'] = $aEntries;

    	$response->setStatusCode($statusCode);
    	$response->setContent(json_encode($result));

    	return $response;
    }
}
