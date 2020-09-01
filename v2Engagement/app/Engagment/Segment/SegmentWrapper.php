<?php

namespace App\Engagment\Segment;

use App\Engagment\Segment\SegmentHandler;

class SegmentWrapper
{
    protected $segmentHandler;

    public function __construct(SegmentHandler $segment)
    {
        $this->segmentHandler = $segment;
    }

    public function segmentListing($request, $companyId)
    {
        return list($totalData, $totalFiltered, $segmentListing) = $this->segmentHandler->segmentListing($request, $companyId);
    }

    public function GetFilters($companyId)
    {
        $filters = array();
        $fields = $this->segmentHandler->GetFields($companyId);

        foreach ($fields as $field) {
            $mainObj = (object)[];
            //$mainObj->id = $field->field_id;
            $mainObj->id = $field->code;
            $mainObj->label = $field->code;

            if ($field->data_type == "VARCHAR") {
                $mainObj->type = 'string';
                if (strtolower($mainObj->label) == "email") {
                    $mainObj->validation = (object)[];
                    $mainObj->validation->callback = '';
                }
            } elseif ($field->data_type == "INT") {
                $mainObj->type = 'integer';
            } elseif ($field->data_type == "DATE") {
                $mainObj->type = 'date';
                $mainObj->validation = (object)[];
                $mainObj->validation->format = 'YYYY/MM/DD';
                $mainObj->plugin = 'datepicker';
                $mainObj->plugin_config = (object)[];
                $mainObj->plugin_config->format = 'yyyy-mm-dd';
                $mainObj->plugin_config->todayBtn = 'linked';
                $mainObj->plugin_config->todayHighlight = true;
                $mainObj->plugin_config->autoclose = true;
            } elseif ($field->data_type == "SELECT") {
                $mainObj->type = 'string';
                $mainObj->input = 'select';
                //$mainObj->input = 'integer';
                $mainObj->values = [];
                $values = $this->segmentHandler->getValues($field, $companyId);
                foreach ($values as $value) {
                    $mainObj->values[$value] = $value;
                }
                $mainObj->operators = ['equal', 'not_equal', 'is_null', 'is_not_null'];
            }
            $filters[] = clone $mainObj;
        }
        return $filters;

    }

    public function saveSegment($segmentArr)
    {
        return $this->segmentHandler->insertInDb($segmentArr);
    }

    public function getSegmentCacheUsers($segmentId)
    {
        $content = "";
        $records = $this->segmentHandler->getSegmentCacheUsers($segmentId);
        foreach ($records as $rec) {
            $content .= implode(", ", $rec) . PHP_EOL;
        }
        return $content;

    }
}