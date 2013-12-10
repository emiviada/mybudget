<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;


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
        $chart->credits->enabled(false);
        $chart->title->text('Últimos 12 meses');
        $chart->xAxis->title(array('text'  => "Meses"));
        $chart->xAxis->categories($months);
        $chart->yAxis->title(array('text'  => "$(pesos)"));
        $chart->yAxis->alternateGridColor('#f8f8f8');
        $chart->series($series);

        //Tooltip
        $formatter = new Expr('function () {
             return this.x + ": $" + this.y;
         }');
        $chart->tooltip->formatter($formatter);
        
        return $this->render('BackendBundle:Chart:progression.html.twig', array(
        	'chart' => $chart
    	));
    }

    /*
     * targets() action
     * @desc Renders the targets chart
     */
    public function targetsAction()
    {
        $request = $this->get('request');
        $apiUrl = $request->getScheme().'://'.$request->getHttpHost().'/api/targets';
        $result = json_decode(file_get_contents($apiUrl), true);

        if ($result['status'] != 200)
            die('ERROR!!');

        $months = array();
        $positive = array();
        $negative = array();

        if ($result['count'] > 0) {

            foreach ($result['results'] as $k => $item) {
                //Get the months to x axis
                $itemDate = new \DateTime($item['month']['date']);
                $mText = $itemDate->format('M Y');
                if (!in_array($mText, $months))
                    $months[] = $mText;

                if (is_null($item['points'])) {
                    $positive[] = $negative[] = 0;
                    continue;
                }

                if ($item['points'] > 0) {
                    $positive[] = (float) $item['points'];
                    $negative[] = 0;
                } else {
                    $negative[] = (float) $item['points'];
                    $positive[] = 0;
                }
            }
        }

        $series = array(
            array("name" => "Positivo", "data" => $positive, "color" => "#55aa55"),
            array("name" => "Negativo", "data" => $negative, "color" => "#dd3333")
        );

        $chart = new Highchart();
        $chart->chart->renderTo('month-targets');  // The #id of the div where to render the chart
        $chart->chart->type('column');
        $chart->credits->enabled(false);
        $chart->plotOptions->series(array('shadow' =>  false));
        $chart->title->text('Objetivos mensuales');
        $chart->xAxis->title(array('text'  => "Meses"));
        $chart->xAxis->categories($months);
        $chart->yAxis->title(array('text'  => "Points"));
        $chart->yAxis->alternateGridColor('#f8f8f8');
        $chart->series($series);

        //Tooltip
        $formatter = new Expr('function () {
             return this.x + ": $" + this.y;
         }');
        $chart->tooltip->formatter($formatter);
        
        return $this->render('BackendBundle:Chart:targets.html.twig', array(
            'chart' => $chart
        ));
    }

    /*
     * byCategory() action
     * @desc Renders the chart by cateogry
     */
    public function byCategoryAction($data, $start)
    {
        $categories = array();
        $acum = array();
        $startMonth = strtotime($start);
        if (count($data['by_month']) > 0) {
            foreach ($data['by_month'] as $year => $months) {
                foreach ($months as $month => $value) {
                    $m = strtotime($year.'-'.$month.'-01');
                    if ($m >= $startMonth) {
                        $acum[$year.'-'.$month] = $value;
                    }
                }
            }
        }
        
        $values = array();
        foreach ($acum as $mKey => $value) {
            $categories[] = date('M Y', mktime(0, 0, 0, substr($mKey, 5, 2), 1, substr($mKey, 0, 4)));
            $values[] = $value;
        }

        $series = array(
            array("name" => "Gastos", "data" => $values, "color" => "#3D96AE")
        );
        
        $chart = new Highchart();
        $chart->chart->renderTo('by-category');  // The #id of the div where to render the chart
        $chart->chart->type('column');
        $chart->credits->enabled(false);
        $chart->plotOptions->series(array('shadow' =>  false));
        $chart->title->text('Por Categoria');
        $chart->xAxis->title(array('text'  => "Meses"));
        $chart->xAxis->categories($categories);
        $chart->yAxis->title(array('text'  => "$(pesos)"));
        $chart->yAxis->alternateGridColor('#f8f8f8');
        $chart->series($series);

        $chart->yAxis->plotLines(array(
            array(
                'value' => $data['average'],
                'id' => 'average',
                'color' => '#cccccc',
                'dashStyle' => 'ShortDash',
                'width' => 1,
                'zIndex' => 0
            )
        ));

        //Tooltip
        $formatter = new Expr('function () {
             return this.x + ": $" + this.y;
         }');
        $chart->tooltip->formatter($formatter);
        
        return $this->render('BackendBundle:Chart:byCategory.html.twig', array(
            'chart' => $chart
        ));
    }
}
