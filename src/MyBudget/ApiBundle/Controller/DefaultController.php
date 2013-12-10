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

    /*** ENTRIES ***/
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

    /*
     * searchEntries Controller
     * @desc Search for entries
     * @return JSON
     */
    public function searchEntriesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
        $searchableFields = array('category_id');

        $response = new Response();

        $queryKeys = $request->query->keys();
        if (count($queryKeys) == 0) {
            $statusCode = 400;
            $message = "You need to provide a valid field to search by.";
            $count = 0;
            $data = array();
        } else {
            $validParamenters = true;
            foreach ($queryKeys as $key) {
                if (!in_array($key, $searchableFields)) {
                    $validParamenters = false;
                    break;
                }
            }
            
            if (!$validParamenters) {
                $statusCode = 400;
                $message = "You need to provide a valid field to search by.";
                $count = 0;
                $data = array();
            } else {
                //search by category_id
                if ($request->query->has('category_id')) {
                    $category_id = $request->query->get('category_id');
                    $category = $em->getRepository('CategoryBundle:Category')->findOneById($category_id);
                    if ($category) {
                        $statusCode = 200;
                        $data = array();
                        $message = "Success";
                        $entries = $category->getEntries();
                        $count = count($entries);
                        if ($count) {
                            foreach ($entries as $entry) {
                                $data[] = $entry->toArray();
                            }
                        }
                        //Get subcategories (If it has)
                        if ($category->isParent()) {
                            $children = $category->getChildren();
                            foreach ($children as $subcategory) {
                                $entries = $subcategory->getEntries();
                                $count = count($entries);
                                if ($count) {
                                    foreach ($entries as $entry) {
                                        $data[] = $entry->toArray();
                                    }
                                }
                            }
                        }
                    } else { //Category NOT FOUND
                        $statusCode = 404;
                        $message = "Category does not be found.";
                        $count = 0;
                        $data = array();
                    }
                }
            }
        }

        $result['status'] = $statusCode;
        $result['message'] = $message;
        $result['count'] = $count;
        $result['results'] = $data;

        $response->setStatusCode($statusCode);
        $response->setContent(json_encode($result));

        return $response;
    }
    /*** END ENTRIES ***/

    /*** TARGETS ***/
    /*
     * getTargets Controller
     * @desc Get the targets (Could be filtered)
     * @return JSON
     */
    public function getTargetsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
        
        $from = ($request->query->has('from'))? new \DateTime($request->query->get('from')) : null;
        $to = ($request->query->has('to'))? new \DateTime($request->query->get('to')) : null;

        $response = new Response();
        
        $targets = $em->getRepository('BackendBundle:Target')->getFromTo($from, $to);
        $aTargets = array();
        $count = count($targets);
        if ($count) {
            foreach ($targets as $target) {
                $aTargets[] = $target->toArray();
            }
        }
        $statusCode = 200;

        $result['status'] = $statusCode;
        $result['message'] = "Success";
        $result['count'] = $count;
        $result['results'] = $aTargets;

        $response->setStatusCode($statusCode);
        $response->setContent(json_encode($result));

        return $response;
    }
    /*** END TARGETS ***/
}
