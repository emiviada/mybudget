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
    public function categoryStatsAction($today)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categoryRepository = $em->getRepository('CategoryBundle:Category');
        $request = $this->get('request');

        //Get categories
        $categories = $categoryRepository->findBy(
            array(), //Criteria (Filtering)
            array('name' => 'asc') //OrderBy (Sortering)
        );

        //Category stats
        $category_id = ($request->query->has('category_id'))? $request->query->get('category_id') :
                        $this->container->getParameter('default_category');
        $apiUrl = $request->getScheme().'://'.$request->getHttpHost().'/api/entries/search?category_id='.$category_id;
        $result = json_decode(file_get_contents($apiUrl), true);
        $records = $result['count'];

        $category_stats = array();
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

            $todayTimestamp = $today->getTimestamp();
            $thisYear = date('Y', $todayTimestamp);
            $thisMonth = date('n', $todayTimestamp);
            $lastMonthTimestamp = $today->modify('-1 month')->getTimestamp();
            $lastYear = date('Y', $lastMonthTimestamp);
            $lastMonth = date('n', $lastMonthTimestamp);
            $startYear = ($lastMonth == 12)? $lastYear : $lastYear - 1;
            $startMonth = ($lastMonth == 12)? 1 : $lastMonth + 1;

            $z = 0;$acum = 0;
            for ($year = $startYear; $year <= $lastYear; $year++) {
                for ($m = $startMonth; $m <= ($startMonth + 11); $m++) {
                    $month = ($m > 12)? $m - 12 : $m;
                    if (isset($category_stats['by_month'][$year][$month])) {
                        $z++;
                        $acum += $category_stats['by_month'][$year][$month];
                    }
                    $year = ($m == 12)? $year + 1 : $year;
                }
            }

            $category_stats['this_month'] = $category_stats['by_month'][$thisYear][$thisMonth];
            $category_stats['average'] = round($acum / $z, 2);
        }

        return $this->render('BackendBundle:Default:category_stats.html.twig', array(
            'categories' => $categories,
            'category_stats' => $category_stats,
            'api_result' => $result['results']
        ));
    }
}
