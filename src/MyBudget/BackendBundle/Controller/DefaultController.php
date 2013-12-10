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
        $categoryRepository = $em->getRepository('CategoryBundle:Category');
        $request = $this->get('request');

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

        //Get targets from last two months
        $targetRepository = $em->getRepository('BackendBundle:Target');
        $targets = $targetRepository->findBy(
            array(), //Criteria (Filtering)
            array('month' => 'desc'), //OrderBy (Sortering)
            2,
            0
        );

        return $this->render('BackendBundle:Default:index.html.twig', array(
        	'since' => $oldest->getDateEntry(),
        	'until' => $newest->getDateEntry(),
        	'balance' => $balance,
            'balance_class' => $balance_class,
            'today' => $today,
            'prevMonth' => $prevMonth,
            'ing' => $ing,
            'out' => $out,
            'targets' => $targets
    	));
    }

    /*
     * categoryStats() action (Component shown on Dashboard)
     * @desc Renders the category stats (and chart)
     */
    public function categoryStatsAction($category_id, $today)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categoryRepository = $em->getRepository('CategoryBundle:Category');
        $request = $this->get('request');
        $inflation = array(
            'monthly' => 0,
            'quarterly' => 0,
            'biannually' => 0,
            'annual' => 0
        );

        if (is_null($today)) {
            $today = new \DateTime();
        }
        $todayTimestamp = $today->getTimestamp();
        $thisYear = date('Y', $todayTimestamp);
        $thisMonth = date('n', $todayTimestamp);
        $lastMonthTimestamp = $today->modify('-1 month')->getTimestamp();
        $lastYear = date('Y', $lastMonthTimestamp);
        $lastMonth = date('n', $lastMonthTimestamp);
        $startYear = ($lastMonth == 12)? $thisYear : $thisYear - 1;
        $startMonth = ($lastMonth == 12)? 1 : $lastMonth + 1;

        //Get categories
        $categories = $categoryRepository->findBy(
            array(), //Criteria (Filtering)
            array('name' => 'asc') //OrderBy (Sortering)
        );

        //Category stats
        $apiUrl = $request->getScheme().'://'.$request->getHttpHost().'/api/entries/search?category_id='.$category_id;
        $result = json_decode(file_get_contents($apiUrl), true);
        $records = $result['count'];

        $category_stats = array(
            'this_month' => 0,
            'average' => 0
        );
        if ($result['status'] == 200 && $records > 0) {
            
            foreach ($result['results'] as $item) {
                
                $datetime = $item['date_entry']['date'];
                $year = (int) substr($datetime, 0, 4);
                $month = (int) substr($datetime, 5, 2);
                $value = (float) $item['value'];
                
                if (isset($category_stats['by_month'][$year][$month])) {
                    $category_stats['by_month'][$year][$month] += $value;
                } else {
                    $category_stats['by_month'][$year][$month] = $value;
                }
            }

            $z = 0;$acum = 0;
            for ($year = $startYear; $year <= $lastYear; $year++) {
                for ($m = $startMonth; $m <= ($startMonth + 11); $m++) {
                    $month = ($m > 12)? $m - 12 : $m;
                    if (isset($category_stats['by_month'][$year][$month])) {
                        $z++;
                        $acum += $category_stats['by_month'][$year][$month];
                    } else {
                        $category_stats['by_month'][$year][$month] = 0;
                    }
                    ksort($category_stats['by_month'][$year]);
                    $year = ($m == 12)? $year + 1 : $year;
                }
            }

            $category_stats['this_month'] = (isset($category_stats['by_month'][$thisYear][$thisMonth]))? 
                                                $category_stats['by_month'][$thisYear][$thisMonth]: 0;
            $category_stats['average'] = ($z > 0)? round($acum / $z, 2) : 0;

            //Inflation calculation
            $lastMonthValue = $category_stats['by_month'][$lastYear][$lastMonth];
            $iMonthlyTimestamp = $today->modify('-1 month')->getTimestamp(); //$today it was already back 1 month
            $iMonthlyYear = date('Y', $iMonthlyTimestamp);
            $iMonthlyMonth = date('n', $iMonthlyTimestamp);
            $iMonthlyValue = $category_stats['by_month'][$iMonthlyYear][$iMonthlyMonth];
            $iQuarterlyTimestamp = $today->modify('-2 month')->getTimestamp(); //$today it was already back 1 month
            $iQuarterlyYear = date('Y', $iQuarterlyTimestamp);
            $iQuarterlyMonth = date('n', $iQuarterlyTimestamp);
            $iQuarterlyValue = $category_stats['by_month'][$iQuarterlyYear][$iQuarterlyMonth];
            $iBiannuallyTimestamp = $today->modify('-3 month')->getTimestamp(); //$today it was already back 2 months
            $iBiannuallyYear = date('Y', $iBiannuallyTimestamp);
            $iBiannuallyMonth = date('n', $iBiannuallyTimestamp);
            $iBiannuallyValue = $category_stats['by_month'][$iBiannuallyYear][$iBiannuallyMonth];
            $iAnnuallyTimestamp = $today->modify('-6 month')->getTimestamp(); //$today it was already back 3 months
            $iAnnuallyYear = date('Y', $iAnnuallyTimestamp);
            $iAnnuallyMonth = date('n', $iAnnuallyTimestamp);
            $iAnnuallyValue = (isset($category_stats['by_month'][$iAnnuallyYear][$iAnnuallyMonth]))? $category_stats['by_month'][$iAnnuallyYear][$iAnnuallyMonth] : 0;
            $inflation = array(
                'monthly' => ($iMonthlyValue > 0 && ($lastMonthValue > $iMonthlyValue))? round(($lastMonthValue / $iMonthlyValue) * 100 - 100, 2) : 0,
                'quarterly' => ($iQuarterlyValue > 0 && ($lastMonthValue > $iQuarterlyValue))? round(($lastMonthValue / $iQuarterlyValue) * 100 - 100, 2) : 0,
                'biannually' => ($iBiannuallyValue > 0 && ($lastMonthValue > $iBiannuallyValue))? round(($lastMonthValue / $iBiannuallyValue) * 100 - 100, 2) : 0,
                'annual' => ($iAnnuallyValue > 0 && ($lastMonthValue > $iAnnuallyValue))? round(($lastMonthValue / $iAnnuallyValue) * 100 - 100, 2) : 0
            );
        }

        return $this->render('BackendBundle:Default:category_stats.html.twig', array(
            'categories' => $categories,
            'category_stats' => $category_stats,
            'selected_category_id' => $category_id,
            'start_chart_from' => $startYear.'-'.$startMonth.'-01',
            'inflation' => $inflation
        ));
    }
}
