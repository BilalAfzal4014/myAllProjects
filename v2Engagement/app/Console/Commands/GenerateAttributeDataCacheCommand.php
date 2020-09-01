<?php

namespace App\Console\Commands;

use App\AttributeData;
use App\Components\CompanyAttributeData;
use App\Jobs\AttributeDataCacheJob;
use App\User;
use App\UserAttribute;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class GenerateAttributeDataCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attribute:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate attribute data cache';

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
        $row_id = $this->option('row_id');
        $segments = ((bool) $this->option('segments') === true) ? true : false;
        $campaigns = ((bool) $this->option('campaigns') === true) ? true : false;
        $newsfeeds = ((bool) $this->option('newsfeed') === true) ? true : false;

        $users = !empty($id) ? User::where('id', $id)->get() : User::all();

        foreach ($users as $user) {
            $attribute_rows = UserAttribute::select('row_id')
                ->where('company_id', $user->id);

            if (!empty($row_id)) {
                $attribute_rows = $attribute_rows->where('row_id', $row_id);
            }

            if ($attribute_rows->count() > 0) {
                $cache_key = "company_".$user->id."_rows";

                $total = $attribute_rows->count();
                $limit = !empty(config('engagement.limit.user_attribute')) ? config('engagement.limit.user_attribute') : 100;
                $attribute_rowIds = collect();

                $counter = 0;
                for ($i=0; $i<=$total; $i+=$limit) {
                    $attributes = $attribute_rows;
                    $attributes = $attributes->skip($i)->take($limit)->get();

                    $rowIds = $attributes->pluck('row_id')->unique()->toArray();
                    foreach ($rowIds as $rowId) {
                        $counter++;
                        $this->comment("Generating Cache for Row {$counter}/{$total}");
                        \Queue::pushOn('cache', new AttributeDataCacheJob($user->id, $rowId));
                        $this->info("Generated Cache for Row {$counter}/{$total}");
                    }

                    $attribute_rowIds = $attribute_rowIds->merge($rowIds);
                }

                $rowIds = $attribute_rowIds->toArray();

                CompanyAttributeData::removeEntry($cache_key);
                \Cache::forever($cache_key, \GuzzleHttp\json_encode(
                    $rowIds
                ));

                $this->info("Attribute data has been cached for company id {$user->id}");

                if ($segments === true) {
                    $this->call('segment:cache', [
                        '--company' => $user->id
                    ]);
                }

                if ($campaigns === true) {
                    $this->call('tracking:cache', [
                        '--company' => $user->id
                    ]);
                }

                if ($newsfeeds === true) {
                    $this->call('newsfeed:cache', [
                        '--company' => $user->id
                    ]);
                }
            } else {
                $this->error("No Attribute data found for company id {$user->id}");
            }
        }

        return;
    }

    /**
     * Configure input parameters.
     */
    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_OPTIONAL, 'Company ID');
        $this->addOption('row_id', null, InputOption::VALUE_OPTIONAL, 'Row ID');
        $this->addOption('segments', null, InputOption::VALUE_OPTIONAL, 'Generate segments cache', false);
        $this->addOption('campaigns', null, InputOption::VALUE_OPTIONAL, 'Generate campaigns cache', false);
        $this->addOption('newsfeed', null, InputOption::VALUE_OPTIONAL, 'Generate newsfeed cache', false);
    }
}
