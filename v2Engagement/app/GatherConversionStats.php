<?php

namespace App;

use App\Components\CampaignTrackingData;
use Carbon\Carbon;

trait GatherConversionStats
{
    private $campaignConversionData;
    private $conversionAppId;

    /**
     * Set campaign ids for a company.
     */
    protected function setCampaignConversionData()
    {
        $this->campaignConversionData = collect();

        if ($this->isCompany === true) {
            $this->campaignConversionData = $this->campaignConversionData->merge(
                CampaignTrackingData::conversions(auth()->user()->id)
            );
        } else {
            $companyRole = Role::findByName(config('engagement.roles.company'));
            if (!empty($companyRole->id)) {
                $companies = $companyRole->users;
                if ($companies->count() > 0) {
                    foreach ($companies as $company) {
                        $this->campaignConversionData = $this->campaignConversionData->merge(
                            CampaignTrackingData::conversions($company->id)
                        );
                    }
                }
            }
        }

        if (isset($this->conversionAppId)) {
            $this->campaignConversionData = $this->campaignConversionData->filter(function ($track) {
                return in_array($track['app_name'], [$this->conversionAppId]) ? $track : null;
            });
        }
    }

    /**
     * @param string $column
     * @param array $range
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getConversionTrackingData($column, $range)
    {
        return $this->campaignConversionData->filter(function ($tracking) use ($column, $range) {
            $startTs = Carbon::parse($range[0])->timestamp;
            $endTs = Carbon::parse($range[1])->timestamp;
            $createdTs = Carbon::parse($tracking[$column])->timestamp;

            return (($createdTs >= $startTs) && ($createdTs <= $endTs)) ? $tracking : null;
        });
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherTodayConversionStats()
    {
        return $this->gatherSingleDayConversionStats(
            Carbon::now()->toDateString()
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherYesterdayConversionStats()
    {
        return $this->gatherSingleDayConversionStats(
            Carbon::now()->subDay()->toDateString()
        );
    }

    /**
     * @param string $now
     * @param int $limit
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected function getFilterzero($array)
    {
        foreach ($array as $array_key => $array_item) {
            if ($array[$array_key] === 0) {
                unset($array[$array_key]);
            }
        }
        return array_values($array);
    }

    protected function gatherSingleDayConversionStats($now, $limit = 24)
    {
        $this->setCampaignConversionData();

        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }
        $dates = [];
        $categories = [];
        $series = [
            [
                'name' => 'ANDROID',
                'data' => $defaultData,
                'color' => $this->colors['android'],
            ],
            [
                'name' => 'IOS',
                'data' => $defaultData,
                'color' => $this->colors['ios'],
            ],
            [
                'name' => 'WEB',
                'data' => $defaultData,
                'color' => $this->colors['web'],
            ]
        ];
        $data = [];

        $startOfDay = Carbon::parse($now)->startOfDay();
        $endOfDay = Carbon::parse($now)->endOfDay();

        $dateFormat = "H:i:s";
        $date = $startOfDay;

        while ($date->endOfHour()->toDateTimeString() <= $endOfDay->toDateTimeString()) {
            $startDate = Carbon::parse($date->toDateTimeString())->startOfHour()->toDateTimeString();
            $endDate = Carbon::parse($date->toDateTimeString())->endOfHour()->toDateTimeString();

            $dates[] = [
                $startDate,
                $endDate
            ];
            $categories[] = Carbon::parse($startDate)->format("g:i A") .
                " - " .
                Carbon::parse($endDate)->format("g:i A");

            $date = Carbon::parse($endDate)->addHour()->startOfHour();
        }

        $interval_text = Carbon::parse($now)->format("D, M j, Y");

        $datesCollection = collect($dates)->flatten();

        $campaign_conversions = $this->getConversionTrackingData('created_at', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if ($campaign_conversions->isEmpty() && ($this->isCompany === true)) {
            $series = [];
            return response()->json([
                'data' => [
                    'text' => "No data for Conversion Stats ($interval_text)",
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($dates as $date) {
            $conversions = $campaign_conversions->filter(function ($track) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($track['created_at'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $track;
                }

                return null;
            });

            if ($conversions->count() > 0) {
                $android = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['android']) ? $conversion : null;
                })->count();

                $ios = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['ios']) ? $conversion : null;
                })->count();

                $web = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['web']) ? $conversion : null;
                })->count();

                $data['android'][] = $android;
                $data['ios'][] = $ios;
                $data['web'][] = $web;
            } else {
                $data['android'][] = 0;
                $data['ios'][] = 0;
                $data['web'][] = 0;
            }
        }

        $series[0]['data'] = array_values($data['android']);
        $series[1]['data'] = array_values($data['ios']);
        $series[2]['data'] = array_values($data['web']);

//        $series[0]['data'] = $this->getFilterzero($data['android']);
//        $series[1]['data'] =$this->getFilterzero($data['ios']);
//        $series[2]['data'] =$this->getFilterzero($data['web']);

        $response = [
            'text' => "Conversion Stats ($interval_text)",
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherMultiDayConversionStats($startDate, $endDate, $limit)
    {
        $this->setCampaignConversionData();

        $dateFormat = "F d, Y";
        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }

        $dates = [];
        $categories = [];
        $series = [
            [
                'name' => 'ANDROID',
                'data' => $defaultData,
                'color' => $this->colors['android'],
            ],
            [
                'name' => 'IOS',
                'data' => $defaultData,
                'color' => $this->colors['ios'],
            ],
            [
                'name' => 'WEB',
                'data' => $defaultData,
                'color' => $this->colors['web'],
            ]
        ];
        $data = [];

        $interval_text = Carbon::parse($startDate)->format($dateFormat) .
            " to " .
            Carbon::parse($endDate)->format($dateFormat);

        $date = Carbon::parse($startDate);

        while ($date->toDateTimeString() <= $endDate) {
            $startDayDate = $date->toDateTimeString();
            $endDayDate = Carbon::parse($date->toDateTimeString())->endOfDay()->toDateTimeString();

            $dates[] = [
                $startDayDate,
                $endDayDate
            ];

            $categories[] = Carbon::parse($startDayDate)->format($dateFormat);

            $date = Carbon::parse($endDayDate)->addDay()->startOfDay();
        }

        $datesCollection = collect($dates)->flatten();

        $campaign_conversions = $this->getConversionTrackingData('created_at', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if ($campaign_conversions->isEmpty() && ($this->isCompany === true)) {
            $series = [];
            return response()->json([
                'data' => [
                    'text' => "No data for Conversion Stats ($interval_text)",
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($dates as $date) {
            $conversions = $campaign_conversions->filter(function ($track) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($track['created_at'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $track;
                }

                return null;
            });

            if ($conversions->count() > 0) {
                $android = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['android']) ? $conversion : null;
                })->count();

                $ios = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['ios']) ? $conversion : null;
                })->count();

                $web = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['web']) ? $conversion : null;
                })->count();

                $data['android'][] = $android;
                $data['ios'][] = $ios;
                $data['web'][] = $web;
            } else {
                $data['android'][] = 0;
                $data['ios'][] = 0;
                $data['web'][] = 0;
            }
        }

        $series[0]['data'] = array_values($data['android']);
        $series[1]['data'] = array_values($data['ios']);
        $series[2]['data'] = array_values($data['web']);

//        $series[0]['data'] = $this->getFilterzero($data['android']);
//        $series[1]['data'] =$this->getFilterzero($data['ios']);
//        $series[2]['data'] =$this->getFilterzero($data['web']);

        $response = [
            'text' => "Conversion Stats ($interval_text)",
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherMultiMonthsConversionStats($startDate, $endDate, $limit)
    {
        $this->setCampaignConversionData();

        $dateFormat = "F Y";
        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }

        $dates = [];
        $categories = [];
        $series = [
            [
                'name' => 'ANDROID',
                'data' => $defaultData,
                'color' => $this->colors['android'],
            ],
            [
                'name' => 'IOS',
                'data' => $defaultData,
                'color' => $this->colors['ios'],
            ],
            [
                'name' => 'WEB',
                'data' => $defaultData,
                'color' => $this->colors['web'],
            ]
        ];
        $data = [];

        $interval_text = Carbon::parse($startDate)->format($dateFormat) .
            " to " .
            Carbon::parse($endDate)->format($dateFormat);

        $date = Carbon::parse($startDate);

        while ($date->toDateTimeString() <= $endDate) {
            $startMonthDate = $date->toDateTimeString();
            $endMonthDate = Carbon::parse($date->toDateTimeString())->endOfMonth()->toDateTimeString();

            $dates[] = [
                $startMonthDate,
                $endMonthDate
            ];

            $categories[] = Carbon::parse($startMonthDate)->format($dateFormat);

            $date = Carbon::parse($endMonthDate)->addDay()->startOfMonth();
        }

        $datesCollection = collect($dates)->flatten();

        $campaign_conversions = $this->getConversionTrackingData('created_at', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if ($campaign_conversions->isEmpty() && ($this->isCompany === true)) {
            $series = [];
            return response()->json([
                'data' => [
                    'text' => "No data for Conversion Stats ($interval_text)",
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($dates as $date) {
            $conversions = $campaign_conversions->filter(function ($track) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($track['created_at'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $track;
                }

                return null;
            });

            if ($conversions->count() > 0) {
                $android = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['android']) ? $conversion : null;
                })->count();

                $ios = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['ios']) ? $conversion : null;
                })->count();

                $web = $conversions->filter(function ($conversion) {
                    return in_array(strtolower($conversion['device_type']), ['web']) ? $conversion : null;
                })->count();

                $data['android'][] = $android;
                $data['ios'][] = $ios;
                $data['web'][] = $web;
            } else {
                $data['android'][] = 0;
                $data['ios'][] = 0;
                $data['web'][] = 0;
            }
        }

        $series[0]['data'] = array_values($data['android']);
        $series[1]['data'] = array_values($data['ios']);
        $series[2]['data'] = array_values($data['web']);

//        $series[0]['data'] = $this->getFilterzero($data['android']);
//        $series[1]['data'] =$this->getFilterzero($data['ios']);
//        $series[2]['data'] =$this->getFilterzero($data['web']);

        $response = [
            'text' => "Conversion Stats ($interval_text)",
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }
}