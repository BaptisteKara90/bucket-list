<?php

namespace App\Helpers;

use App\Form\Model\SearchEvent;

class ProcessData
{
    public function getData(SearchEvent $event)
    {
        $baseUrl = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records?limit=20';


        if ($event->getCity()){
            $baseUrl .= '&refine=location_city:"'. $event->getCity().'"';

        }

        return $data = json_decode(file_get_contents($baseUrl));
    }
}