<?php

namespace App;

use Carbon\Carbon;

trait GatheringAndroidIosStats
{
    /**
     * Set newsfeed ids for a company.
     */
    protected function setNewsFeedIds()
    {
        if ($this->isCompany === true) {
            $newsfeeds = auth()->user()->newsfeeds;

            $this->newsfeedIds = $newsfeeds->pluck('id')
                ->flatten()->toArray();
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
        if ($this->isCompany === true) {
            return LinkTracking::where('device', 'android')
                ->whereIn('rec_id', $this->newsfeedIds)
                ->whereBetween($column, $range)->get();
        }

        return LinkTracking::where('device', 'android')
            ->whereBetween($column, $range)->get();
    }

    /**
     * @param string $column
     * @param array $range
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getNewsfeedViewsData($column, $range)
    {
        if ($this->isCompany === true) {
            return LinkTracking::where('device', 'iphone')
                ->whereIn('rec_id', $this->newsfeedIds)
                ->whereBetween($column, $range)->get();
        }

        return LinkTracking::where('device', 'iphone')
            ->whereBetween($column, $range)->get();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function gatherTodayNewsfeedStats()
    {
        //dd(Carbon::now()->toDateString());
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
        $dates = [];
        $categories = [];
        $series = [
            [
                'name' => 'Clicks',
                'data' => [0, 0],
                'color' => $this->colors['clicks'],
            ],
            [
                'name' => 'Views',
                'data' => [0, 0],
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

        $interval_text = "Newsfeed (" . Carbon::parse($now)->format("D, M j, Y") . ")";

        if (empty($this->newsfeedIds) && ($this->isCompany === true)) {
            return response()->json([
                'data' => [
                    'text' => $interval_text,
                    'categories' => $categories,
                    'series' => $series,
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

        foreach ($dates as $date) {
            $clicks = $newsfeed_clicks->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed->created_date)->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $views = $newsfeed_views->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed->created_date)->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $data['clicks'][] = $clicks->count();
            $data['views'][] = $views->count();
        }

        $series[0]['data'] = array_values($data['clicks']);
        $series[1]['data'] = array_values($data['views']);

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

        if (empty($this->newsfeedIds) && ($this->isCompany === true)) {
            return response()->json([
                'data' => [
                    'text' => $interval_text,
                    'categories' => $categories,
                    'series' => $series,
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

        foreach ($dates as $date) {
            $clicks = $newsfeed_clicks->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed->created_date)->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $views = $newsfeed_views->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed->created_date)->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $data['clicks'][] = $clicks->count();
            $data['views'][] = $views->count();
        }

        $series[0]['data'] = array_values($data['clicks']);
        $series[1]['data'] = array_values($data['views']);

        $response = [
            'text' => "Newsfeed ($interval_text)",
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
                    'text' => $interval_text,
                    'categories' => $categories,
                    'series' => $series,
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

        foreach ($dates as $date) {
            $clicks = $newsfeed_clicks->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed->created_date)->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $views = $newsfeed_views->filter(function ($feed) use ($date) {
                $startTs = Carbon::parse($date[0])->timestamp;
                $endTs = Carbon::parse($date[1])->timestamp;
                $createdTs = Carbon::parse($feed->created_date)->timestamp;

                if (($createdTs >= $startTs) && ($createdTs <= $endTs)) {
                    return $feed;
                }

                return null;
            });

            $data['clicks'][] = $clicks->count();
            $data['views'][] = $views->count();
        }

        $series[0]['data'] = array_values($data['clicks']);
        $series[1]['data'] = array_values($data['views']);

        $response = [
            'text' => "Newsfeed ($interval_text)",
            'categories' => $categories,
            'series' => $series,
        ];

        return response()->json(['data' => $response]);
    }
}