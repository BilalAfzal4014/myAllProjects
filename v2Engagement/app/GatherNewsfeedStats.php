<?php

namespace App;

use App\Components\NewsfeedTrackingData;
use Carbon\Carbon;

trait GatherNewsfeedStats
{
    private $newsfeedClicksData;
    private $newsfeedViewsData;

    /**
     * Set newsfeed ids for a company.
     */
    protected function setNewsFeedIds()
    {
        $this->newsfeedClicksData = collect();
        $this->newsfeedViewsData = collect();

        if ($this->isCompany === true) {
            $trackings = NewsfeedTrackingData::newsfeeds(auth()->user()->id);

            if (!empty($trackings['clicks'])) {
                $this->newsfeedClicksData = $this->newsfeedClicksData->merge($trackings['clicks']);
            }

            if (!empty($trackings['views'])) {
                $this->newsfeedViewsData = $this->newsfeedViewsData->merge($trackings['views']);
            }
        } else {
            $companyRole = Role::findByName(config('engagement.roles.company'));
            if (!empty($companyRole->id)) {
                $companies = $companyRole->users;
                if ($companies->count() > 0) {
                    foreach ($companies as $company) {
                        $trackings = NewsfeedTrackingData::newsfeeds($company->id);

                        if (!empty($trackings['clicks'])) {
                            $this->newsfeedClicksData = $this->newsfeedClicksData->merge($trackings['clicks']);
                        }

                        if (!empty($trackings['views'])) {
                            $this->newsfeedViewsData = $this->newsfeedViewsData->merge($trackings['views']);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $column
     * @param array $range
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getNewsfeedClicksData($column, $range)
    {
        return $this->newsfeedClicksData->filter(function ($tracking) use ($column, $range) {
            $startTs = Carbon::parse($range[0])->timestamp;
            $endTs = Carbon::parse($range[1])->timestamp;
            $createdTs = Carbon::parse($tracking[$column])->timestamp;

            return (($createdTs >= $startTs) && ($createdTs <= $endTs)) ? $tracking : null;
        });
    }

    /**
     * @param string $column
     * @param array $range
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getNewsfeedViewsData($column, $range)
    {
        return $this->newsfeedViewsData->filter(function ($tracking) use ($column, $range) {
            $startTs = Carbon::parse($range[0])->timestamp;
            $endTs = Carbon::parse($range[1])->timestamp;
            $createdTs = Carbon::parse($tracking[$column])->timestamp;

            return (($createdTs >= $startTs) && ($createdTs <= $endTs)) ? $tracking : null;
        });
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherTodayNewsfeedStats()
    {
        return $this->gatherSingleDayNewsfeedStats(
            Carbon::now()->toDateString()
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherYesterdayNewsfeedStats()
    {
        return $this->gatherSingleDayNewsfeedStats(
            Carbon::now()->subDay()->toDateString()
        );
    }

    /**
     * @param string $now
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherSingleDayNewsfeedStats($now)
    {
        $this->setNewsFeedIds();
        $dumyArray = [];
        $dates = [];
        $categories = [];
        // $array = [0, 0, 2, 5, 6, 8, 0, 0, 0];

        $series = [
            [
                'name' => 'Clicks',
                'data' => [0, 0, 2, 5],
                'color' => $this->colors['clicks'],
            ],
            [
                'name' => 'Views',
                'data' => [0, 0, 2, 5],
                'color' => $this->colors['views'],
            ],
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

        $interval_text = "Newsfeed - (" . Carbon::parse($now)->format("D, M j, Y") . ")";

        $datesCollection = collect($dates)->flatten();

        $newsfeed_clicks = $this->getNewsfeedClicksData('created_date', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        $newsfeed_views = $this->getNewsfeedViewsData('created_date', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if ($this->newsfeedClicksData->isEmpty() && $this->newsfeedViewsData->isEmpty()) {
            return response()->json([
                'data' => [
                    'text' => 'No data for ' . $interval_text,
                    'categories' => $categories,
                    'series' => [],
                ]
            ]);
        }

        foreach ($dates as $date) {
            $clicks = $newsfeed_clicks->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed['created_date'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $views = $newsfeed_views->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed['created_date'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $data['clicks'][] = $clicks->count();
            $data['views'][] = $views->count();
        }
        $series[0]['data'] = $data['clicks'];
        $series[1]['data'] = $data['views'];

        $response = [
            'text' => $interval_text,
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
    protected function gatherMultiDayNewsfeedStats($startDate, $endDate, $limit)
    {
        $this->setNewsFeedIds();

        $dateFormat = "F d, Y";
        $dates = [];
        $categories = [];

        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }

        $series = [
            [
                'name' => 'Clicks',
                'data' => $defaultData,
                'color' => $this->colors['clicks'],
            ],
            [
                'name' => 'Views',
                'data' => $defaultData,
                'color' => $this->colors['views'],
            ],
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

        $newsfeed_clicks = $this->getNewsfeedClicksData('created_date', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        $newsfeed_views = $this->getNewsfeedViewsData('created_date', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if ($this->newsfeedClicksData->isEmpty() && $this->newsfeedViewsData->isEmpty()) {
            return response()->json([
                'data' => [
                    'text' => 'No data for Newsfeed' . ' - ' . "($interval_text)",
                    'categories' => $categories,
                    'series' => [],
                ]
            ]);
        }

        foreach ($dates as $date) {
            $clicks = $newsfeed_clicks->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed['created_date'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $views = $newsfeed_views->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed['created_date'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $data['clicks'][] = $clicks->count();
            $data['views'][] = $views->count();
        }

        $series[0]['data'] = $data['clicks'];
        $series[1]['data'] = $data['views'];

        $response = [
            'text' => 'Newsfeed'.'-'."($interval_text)",
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
    protected function gatherMultiMonthNewsfeedStats($startDate, $endDate, $limit)
    {
        $this->setNewsFeedIds();

        $dates = [];
        $dateFormat = "F Y";

        $defaultData = [];
        for ($i = 1; $i <= $limit; $i++) {
            $defaultData[] = 0;
        }

        $categories = [];
        $series = [
            [
                'name' => 'Clicks',
                'data' => $defaultData,
                'color' => $this->colors['clicks'],
            ],
            [
                'name' => 'Views',
                'data' => $defaultData,
                'color' => $this->colors['views'],
            ],
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

        if (empty($this->newsfeedIds) && ($this->isCompany === true)) {
            return response()->json([
                'data' => [
                    'text' => 'No data for Newsfeed' . ' - ' . "($interval_text)",
                    'categories' => $categories,
                    'series' => [],
                ]
            ]);
        }

        $datesCollection = collect($dates)->flatten();

        $newsfeed_clicks = $this->getNewsfeedClicksData('created_date', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        $newsfeed_views = $this->getNewsfeedViewsData('created_date', [
            $datesCollection->first(),
            $datesCollection->last()
        ]);

        if ($this->newsfeedClicksData->isEmpty() && $this->newsfeedViewsData->isEmpty()) {
            return response()->json([
                'data' => [
                    'text' => 'Newsfeed' . '-' . "($interval_text)",
                    'categories' => $categories,
                    'series' => $series,
                ]
            ]);
        }

        foreach ($dates as $date) {
            $clicks = $newsfeed_clicks->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed['created_date'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $views = $newsfeed_views->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed['created_date'])->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $data['clicks'][] = $clicks->count();
            $data['views'][] = $views->count();
        }
        $series[0]['data'] = $data['clicks'];
        $series[1]['data'] = $data['views'];
        $response = [
            'text' => 'Newsfeed'.'-'."($interval_text)",
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }
}