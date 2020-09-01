<?php

namespace App;

use App\Components\CampaignTrackingData;
use Carbon\Carbon;

trait GatherCampaignStats
{
    private $campaignTrackingData;

    /**
     * Set campaign ids for a company.
     */
    protected function setCampaignIds()
    {
        $this->campaignTrackingData = collect();

        if ($this->isCompany === true) {
            $this->campaignTrackingData = $this->campaignTrackingData->merge(
                CampaignTrackingData::campaigns(auth()->user()->id)
            );
        } else {
            $companyRole = Role::findByName(config('engagement.roles.company'));
            if (!empty($companyRole->id)) {
                $companies = $companyRole->users;
                if ($companies->count() > 0) {
                    foreach ($companies as $company) {
                        $this->campaignTrackingData = $this->campaignTrackingData->merge(
                            CampaignTrackingData::campaigns($company->id)
                        );
                    }
                }
            }
        }
    }

    /**
     * @param string $column
     * @param array $range
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getCampaignTrackingData($column, $range)
    {
        ini_set('memory_limit',config('engagement.memory_limit'));
        ini_set('max_execution_time',config('engagement.max_execution_time'));
        
        return $this->campaignTrackingData->filter(function ($tracking) use ($column, $range) {

            $startTs = Carbon::parse($range[0])->timestamp;
            $endTs = Carbon::parse($range[1])->timestamp;
            $createdTs = Carbon::parse($tracking[$column])->timestamp;

            return (($createdTs >= $startTs) && ($createdTs <= $endTs)) ? $tracking : null;
        });
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherTodayCampaignStats()
    {
        return $this->gatherSingleDayCampaignStats(
            Carbon::now()->toDateString()
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherYesterdayCampaignStats()
    {
        return $this->gatherSingleDayCampaignStats(
            Carbon::now()->subDay()->toDateString()
        );
    }


    /**
     * @param string $startDate
     * @param string $endDate
     * @param int $limit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherMultiDayCampaignStats($startDate, $endDate, $limit)
    {
        $this->setCampaignIds();

        $dateFormat = "F d, Y";
        $dates = [];
        $categories = [];

        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }

        $sampleData = [
            [
                'name' => 'Sent',
                'data' => $defaultData,
                'color' => $this->colors['sent'],
            ],
            [
                'name' => 'Failed',
                'data' => $defaultData,
                'color' => $this->colors['failed'],
            ],
            [
                'name' => 'Queued',
                'data' => $defaultData,
                'color' => $this->colors['queued'],
            ]
        ];

        $series = [
            'email' => $sampleData,
            'push' => $sampleData,
            'inapp' => $sampleData
        ];

        $typeColumns = [
            'email' => 'email',
            'push' => 'device_key',
            'inapp' => 'firebase_key'
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
        //dd($datesCollection);
        $trackings = $this->getCampaignTrackingData('created_at', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if (($trackings->isEmpty()) && ($this->isCompany === true)) {
            $series = [
                'email' => [],
                'push' => [],
                'inapp' => []
            ];
            return response()->json([
                'data' => [
                    'text' => [
                        'email' => "No data for Email - ($interval_text)",
                        'push' => "Push  - ($interval_text)",
                        'inapp' => "InApp - ($interval_text)",
                    ],
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($typeColumns as $type => $column) {
            foreach ($dates as $date) {
                $tracks = $trackings->filter(function ($track) use ($date) {
                    $startTs = Carbon::parse($date[0])->timestamp;
                    $endTs = Carbon::parse($date[1])->timestamp;
                    $createdTs = Carbon::parse($track['created_at'])->timestamp;

                    return (($createdTs >= $startTs) && ($createdTs <= $endTs)) ? $track : null;
                });

                if ($tracks->count() > 0) {
                    $sent = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === true)) ? $track : null;
                    })->count();

                    $failed = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === false) && isset($track['logs']['id'])) ? $track : null;
                    })->count();

                    $queued = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === false) && !isset($track['logs']['id'])) ? $track : null;
                    })->count();

                    $data[$type]['sent'][] = $sent;
                    $data[$type]['failed'][] = $failed;
                    $data[$type]['queued'][] = $queued;
                } else {
                    $data[$type]['sent'][] = 0;
                    $data[$type]['failed'][] = 0;
                    $data[$type]['queued'][] = 0;
                }
            }

            $series[$type][0]['data'] = array_values($data[$type]['sent']);
            $series[$type][1]['data'] = array_values($data[$type]['failed']);
            $series[$type][2]['data'] = array_values($data[$type]['queued']);
        }

        $response = [
            'text' => [
                'email' => "Email - ($interval_text)",
                'push' => "Push  - ($interval_text)",
                'inapp' => "InApp - ($interval_text)",
            ],
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }

    /**
     * @param string $now
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherSingleDayCampaignStats($now)
    {
        ini_set('memory_limit',config('engagement.memory_limit'));
        ini_set('max_execution_time',config('engagement.max_execution_time'));
        
        $this->setCampaignIds();

        $dates = [];
        $categories = [];
        $sampleData = [
            [
                'name' => 'Sent',
                'data' => [0, 0, 2, 3, 4, 5, 0, 0],
                'color' => $this->colors['sent'],
            ],
            [
                'name' => 'Failed',
                'data' => [0, 0, 2, 3, 4, 5, 0, 0],
                'color' => $this->colors['failed'],
            ],
            [
                'name' => 'Queued',
                'data' => [0, 0, 2, 3, 4, 5, 10, 0],
                'color' => $this->colors['queued'],
            ]
        ];

        $series = [
            'email' => $sampleData,
            'push' => $sampleData,
            'inapp' => $sampleData
        ];

        $typeColumns = [
            'email' => 'email',
            'push' => 'device_key',
            'inapp' => 'firebase_key'
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

        $trackings = $this->getCampaignTrackingData('created_at', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if (($trackings->isEmpty()) && ($this->isCompany === true)) {
            $series = [
                'email' => [],
                'push' => [],
                'inapp' => []
            ];
            return response()->json([
                'data' => [
                    'text' => [
                        'email' => "No data for Email - ($interval_text)",
                        'push' => "No data for Push  - ($interval_text)",
                        'inapp' => "No data for InApp - ($interval_text)"
                    ],
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($typeColumns as $type => $column) {
            foreach ($dates as $date) {
                $tracks = $trackings->filter(function ($track) use ($date) {
                    $startTs = Carbon::parse($date[0])->timestamp;
                    $endTs = Carbon::parse($date[1])->timestamp;
                    $createdTs = Carbon::parse($track['created_at'])->timestamp;

                    if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                        return $track;
                    }

                    return null;
                });

                if ($tracks->count() > 0) {
                    $sent = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === true)) ? $track : null;
                    })->count();

                    $failed = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === false) && isset($track['logs']['id'])) ? $track : null;
                    })->count();

                    $queued = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === false) && !isset($track['logs']['id'])) ? $track : null;
                    })->count();

                    $data[$type]['sent'][] = $sent;
                    $data[$type]['failed'][] = $failed;
                    $data[$type]['queued'][] = $queued;
                } else {
                    $data[$type]['sent'][] = 0;
                    $data[$type]['failed'][] = 0;
                    $data[$type]['queued'][] = 0;
                }
            }

            $series[$type][0]['data'] = array_values($data[$type]['sent']);
            $series[$type][1]['data'] = array_values($data[$type]['failed']);
            $series[$type][2]['data'] = array_values($data[$type]['queued']);
        }

        $response = [
            'text' => [
                'email' => "Email - ($interval_text)",
                'push' => "Push  - ($interval_text)",
                'inapp' => "InApp - ($interval_text)"
            ],
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
    protected function gatherMultiMonthsCampaignStats($startDate, $endDate, $limit)
    {
        $this->setCampaignIds();

        $dates = [];
        $dateFormat = "F Y";

        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }

        $categories = [];
        $sampleData = [
            [
                'name' => 'Sent',
                'data' => $defaultData,
                'color' => $this->colors['sent'],
            ],
            [
                'name' => 'Failed',
                'data' => $defaultData,
                'color' => $this->colors['failed'],
            ],
            [
                'name' => 'Queued',
                'data' => $defaultData,
                'color' => $this->colors['queued'],
            ]
        ];

        $series = [
            'email' => $sampleData,
            'push' => $sampleData,
            'inapp' => $sampleData
        ];

        $typeColumns = [
            'email' => 'email',
            'push' => 'device_key',
            'inapp' => 'firebase_key'
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

        $trackings = $this->getCampaignTrackingData('created_at', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if (($trackings->isEmpty()) && ($this->isCompany === true)) {
            $series = [
                'email' => [],
                'push' => [],
                'inapp' => []
            ];
            return response()->json([
                'data' => [
                    'text' => [
                        'email' => "No data for Email - ($interval_text)",
                        'push' => "No data for Push  - ($interval_text)",
                        'inapp' => "No data for InApp - ($interval_text)"
                    ],
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($typeColumns as $type => $column) {
            foreach ($dates as $date) {
                $tracks = $trackings->filter(function ($track) use ($date) {
                    $startTs = Carbon::parse($date[0])->timestamp;
                    $endTs = Carbon::parse($date[1])->timestamp;
                    $createdTs = Carbon::parse($track['created_at'])->timestamp;

                    if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                        return $track;
                    }

                    return null;
                });

                if ($tracks->count() > 0) {
                    $sent = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === true)) ? $track : null;
                    })->count();

                    $failed = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === false) && isset($track['logs']['id'])) ? $track : null;
                    })->count();

                    $queued = $tracks->filter(function ($track) use ($date, $column) {
                        return (isset($track[$column]) && ((bool)$track['sent'] === false) && !isset($track['logs']['id'])) ? $track : null;
                    })->count();

                    $data[$type]['sent'][] = $sent;
                    $data[$type]['failed'][] = $failed;
                    $data[$type]['queued'][] = $queued;
                } else {
                    $data[$type]['sent'][] = 0;
                    $data[$type]['failed'][] = 0;
                    $data[$type]['queued'][] = 0;
                }
            }

            $series[$type][0]['data'] = array_values($data[$type]['sent']);
            $series[$type][1]['data'] = array_values($data[$type]['failed']);
            $series[$type][2]['data'] = array_values($data[$type]['queued']);

        }

        $response = [
            'text' => [
                'email' => "Email - ($interval_text)",
                'push' => "Push  - ($interval_text)",
                'inapp' => "InApp - ($interval_text)"
            ],
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }
}