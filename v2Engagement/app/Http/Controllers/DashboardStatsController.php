<?php

namespace App\Http\Controllers;

use App\GatheringAndroidIosStats;
use Illuminate\Http\Request;
use App\Http\Requests;


class DashboardStatsController extends Controller
{
    //
    use GatheringAndroidIosStats;

    public function __construct()
    {
        $this->middleware('auth');

        if (auth()->user()) {
            if (auth()->user()->hasRole('COMPANY')) {
                $this->campaignIds = auth()->user()
                    ->campaigns()->pluck('id')
                    ->flatten()->toArray();

                $this->newsfeedIds = auth()->user()
                    ->newsfeeds()->pluck('id')
                    ->flatten()->toArray();

                $this->isCompany = true;
            }
        }
    }

    public function index()
    {

    }

    public function store(Request $request)
    {
        $response = [];
        $data = $request->all();

        switch ($data['filter']) {
            case 'newsfeed':
                $response = $this->gatherNewsfeedStats($data);
                break;
        }

        return $response;
    }


    protected function gatherNewsfeedStats($data)
    {
        $response = [];

        try {
            switch ($data['interval']) {
                case 'today':
                    $response = $this->gatherTodayNewsfeedStats();
                    break;

                case 'yesterday':
                    $response = $this->gatherYesterdayNewsfeedStats();
                    break;

                case 'last-7-days':
                case 'last-30-days':
                    $limit = 7;
                    if ($data['interval'] == 'last-30-days') {
                        $limit = 30;
                    }

                    $startDate = Carbon::now()->subDays($limit)->startOfDay()->toDateTimeString();
                    $endDate = Carbon::now()->subDay()->endOfDay()->toDateTimeString();

                    $response = $this->gatherMultiDayNewsfeedStats($startDate, $endDate, $limit);

                    break;

                case 'last-3-months':
                case 'last-6-months':
                case 'last-12-months':
                    $limit = 3;
                    if ($data['interval'] == 'last-6-months') {
                        $limit = 6;
                    }
                    if ($data['interval'] == 'last-12-months') {
                        $limit = 12;
                    }

                    $startMonth = Carbon::now()->subMonths($limit)->startOfMonth()->toDateTimeString();
                    $endMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();

                    $response = $this->gatherMultiMonthNewsfeedStats($startMonth, $endMonth, $limit);

                    break;
            }

            return $response;
        } catch (\Exception $exception) {

        }

        return $response;
    }

}
