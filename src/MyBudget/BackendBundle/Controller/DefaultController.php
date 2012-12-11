<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;


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

        //Chart proof of concept
        $series = array(
            array("name" => "Data Serie Name",    "data" => array(1,2,4,5,6,3,8))
        );

        $chart = new Highchart();
        $chart->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $chart->title->text('Chart Title');
        $chart->xAxis->title(array('text'  => "Horizontal axis title"));
        $chart->yAxis->title(array('text'  => "Vertical axis title"));
        $chart->series($series);
        
        return $this->render('BackendBundle:Default:index.html.twig', array(
        	'since' => $oldest->getDateEntry(),
        	'until' => $newest->getDateEntry(),
        	'balance' => $balance,
            'balance_class' => $balance_class,
            'chart' => $chart
    	));
    }
}
