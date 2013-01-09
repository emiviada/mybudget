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
        $request = $this->get('request');

        $today = new \DateTime();
        $lastMonth = $today->modify('-1 month')->format('Y-m-t');
        $aYearAgo = $today->modify('-11 month')->format('Y-m-01');

        $apiUrl = $request->getScheme().'://'.$request->getHttpHost().'/api/entries?from='.$aYearAgo.'&to='.$lastMonth;
        $result = json_decode(file_get_contents($apiUrl), true);

        if ($result['status'] != 200)
            die('ERROR!!');

        $months = array();
        $output = array();
        $input = array();

        if ($result['count'] > 0) {

            foreach ($result['results'] as $k => $item) {
                //Get the months to x axis
                $itemDate = new \DateTime($item['date_entry']['date']);
                $mText = $itemDate->format('M Y');
                if (!in_array($mText, $months))
                    $months[] = $mText;

                //Calculate input and output by month
                if ($item['haber']) {
                    $input[$mText] = (isset($input[$mText]))? $input[$mText] + $item['value'] : 0 + $item['value'];
                } else {
                    $output[$mText] = (isset($output[$mText]))? $output[$mText] + $item['value'] : 0 + $item['value'];
                }
            }

        } else {
            $series = array();
        }

        $inChartData = $outChartData = array();
        foreach ($input as $m => $value) {
            $inChartData[] = $value;
        }
        foreach ($output as $m => $value) {
            $outChartData[] = $value;
        }

        $series = array(
            array("name" => "Ingresos", "data" => $inChartData, "color" => "#55aa55"),
            array("name" => "Gastos", "data" => $outChartData, "color" => "#dd3333")
        );

        $chart = new Highchart();
        $chart->chart->renderTo('twelve-months');  // The #id of the div where to render the chart
        $chart->chart->type('area');
        $chart->title->text('Últimos 12 meses');
        $chart->xAxis->title(array('text'  => "Meses"));
        $chart->xAxis->categories($months);
        $chart->yAxis->title(array('text'  => "$(pesos)"));
        $chart->series($series);
        
        return $this->render('BackendBundle:Chart:progression.html.twig', array(
        	'chart' => $chart
    	));
    }
}
