<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;


class ChartController extends Controller
{
    
    /*
     * progression() action
     * @desc Renders the progression chart
     */
    public function progressionAction()
    {
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
        
        return $this->render('BackendBundle:Chart:progression.html.twig', array(
        	'chart' => $chart
    	));
    }
}
