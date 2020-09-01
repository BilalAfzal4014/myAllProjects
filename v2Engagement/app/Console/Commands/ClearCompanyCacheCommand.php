<?php

namespace App\Console\Commands;

use App\Components\CompanyAttributeData;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ClearCompanyCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cache for Engagement Platform';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->option('company');
        $rowId = $this->option('row_id');
        $remove_app = ((bool)$this->option('remove-apps') === true) ? true : false;

        $users = !empty($id) ? User::where('id', $id)->get() : User::all();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                $rows = collect(CompanyAttributeData::rows($user->id));

                if (!empty($rowId)) {
                    $rows = $rows->filter(function ($row) use($rowId) {
                        return in_array($row['row_id'], [$rowId]) ? $row : null;
                    });
                }

                if ($rows->count() > 0) {
                    $row_ids = $rows->keys();

                    $counter = 0;
                    $count = $rows->count();
                    foreach ($row_ids as $row_id) {
                        $counter++;
                        $this->comment("Removing attribute data {$counter}/{$count} for company id {$user->id}");

                        if ($remove_app === true) {
                            $app_name = $rows->map(function ($attribute) use($row_id) {
                                return ($attribute['row_id'] == $row_id) ? strtolower(trim($attribute['app_name'])) : null;
                            })->filter()->first();

                            $user_id = $rows->map(function ($attribute) use($row_id) {
                                return ($attribute['row_id'] == $row_id) ? $attribute['user_id'] : null;
                            })->filter()->first();

                            CompanyAttributeData::removeUser($user->id, $app_name, $user_id);
                        }

                        $cache_key = "company_" . $user->id . "_row_details_" . $row_id;
                        CompanyAttributeData::removeEntry($cache_key);

                        $cache_key = "company_" . $user->id . "_row_data_" . $row_id;
                        CompanyAttributeData::removeEntry($cache_key);

                        $this->info("Removed attribute data {$counter}/{$count} for company id {$user->id}");
                    }

                    if (empty($rowId)) {
                        $cache_key = "company_" . $user->id . "_rows";
                        CompanyAttributeData::removeEntry($cache_key);
                    }

                    $this->info("Removed attribute data cache for company id {$user->id}");

                    if (empty($rowId)) {
                        $cache_key = "company_" . $user->id . "_segments";
                        $data = \Cache::get($cache_key);

                        if (!empty($data)) {
                            $data = \GuzzleHttp\json_decode($data, true);
                            foreach ($data as $segment_id) {
                                $segment_cache_key = "company_" . $user->id . "_segment_" . $segment_id . "_rows";
                                CompanyAttributeData::removeEntry($segment_cache_key);
                            }

                            CompanyAttributeData::removeEntry($cache_key);

                            $this->info("Removed segments data cache for company id {$user->id}");
                        } else {
                            $this->error("Unable to remove segments data cache for company id {$user->id}");
                        }

                        $cache_key = "company_" . $user->id . "_campaigns";
                        $data = \Cache::get($cache_key);

                        if (!empty($data)) {
                            $campaigns = \GuzzleHttp\json_decode($data, true);

                            foreach ($campaigns as $campaign) {
                                $tracking_cache_key = "company_{$user->id}_campaign_{$campaign}_tracking";
                                CompanyAttributeData::removeEntry($tracking_cache_key);

                                $conversion_cache_key = "company_{$user->id}_campaign_{$campaign}_conversions";
                                CompanyAttributeData::removeEntry($conversion_cache_key);
                            }

                            CompanyAttributeData::removeEntry($cache_key);

                            $this->info("Removed campaign data cache for company id {$user->id}");
                        } else {
                            $this->error("Unable to remove campaign data cache for company id {$user->id}");
                        }
                    }
                } else {
                    $this->error("Unable to remove attribute data cache for company id {$user->id}");
                }
            }
        }
    }

    /**
     * Configure input parameters.
     */
    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_REQUIRED, 'Company ID');
        $this->addOption('row_id', null, InputOption::VALUE_OPTIONAL, 'Row ID');
        $this->addOption('remove-apps', null, InputOption::VALUE_OPTIONAL, 'Remove user apps data');
    }
}
