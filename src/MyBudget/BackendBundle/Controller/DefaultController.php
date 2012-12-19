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
        $entryRepository = $em->getRepository('EntryBundle:Entry');

        $oldest = $entryRepository->getOldest();
        $newest = $entryRepository->getNewest();
        $balance = $entryRepository->getBalance();
        $balance_class = ($balance < 0)? 'negative' : 'positive';

        $today = new \DateTime();
        $entries = $entryRepository->getFromTo(
            new \DateTime($today->format('Y-m-01')),
            new \DateTime($today->format('Y-m-t'))
        );
        
        $ing = $out = 0;
        if (count($entries)) {
            foreach ($entries as $entry) {
                if ($entry->getHaber())
                    $ing += $entry->getValue();
                else
                    $out += $entry->getValue();
            }
        }

        //Chart proof of concept
        $series = array(
            array("name" => "Gastos", "data" => array(1,2,4,5,6,3,8,7,6,7,8,9)),
            array("name" => "Ingresos", "data" => array(1.5,2,3,6,6,5,9,8,7,7,9,9))
        );

        $chart = new Highchart();
        $chart->chart->renderTo('linechart');  // The #id of the div where to render the chart
        $chart->chart->type('area');
        $chart->title->text('Últimos 12 meses');
        $chart->xAxis->title(array('text'  => "Meses"));
        $chart->xAxis->categories(array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'));
        $chart->yAxis->title(array('text'  => "$$$"));
        $chart->series($series);
        
        return $this->render('BackendBundle:Default:index.html.twig', array(
        	'since' => $oldest->getDateEntry(),
        	'until' => $newest->getDateEntry(),
        	'balance' => $balance,
            'balance_class' => $balance_class,
            'today' => $today,
            'ing' => $ing,
            'out' => $out,
            'chart' => $chart
    	));
    }
}
