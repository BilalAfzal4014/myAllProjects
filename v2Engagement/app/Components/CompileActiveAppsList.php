<?php

namespace App\Components;

trait CompileActiveAppsList
{
    /**
     * Compile list of active apps.
     *
     * @param \Illuminate\Support\Collection $apps
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getActiveApps($apps)
    {
        if ($apps->count() > 0) {
            $apps = $apps->filter(function ($app) {
                return (((bool)$app->is_active === true) && ((bool)$app->is_deleted === false)) ? $app : null;
            });
        }

        return $apps;
    }
}
