<?php

namespace App\Console\Commands;

use App\AttributeData;
use App\Components\CompanyAttributeData;
use App\Components\RunExternalCommand;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class AttributeDataImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attribute:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import attribute data from a file';

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
        try {
            $now = Carbon::now();

            $file = $this->option('file');
            if (!file_exists($file)) {
                $file = storage_path('app/public/'.$file);
            }
            $company = $this->option('company');

            $this->comment("Loading excel sheet");

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setLoadSheetsOnly(["user"]);
            $spreadsheet = $reader->load($file);
            $items = $spreadsheet->getActiveSheet()->toArray();

            $this->info("Loaded excel sheet");
            $this->comment("Generating columns & values");

            $codes = $items[0];
            unset($items[0]);

            $rows = AttributeData::where('company_id', $company);

            $replaceData = false;
            if ($rows->count() > 0) {
                $replaceData = true;
                $rows = $rows->get();
            }

            $columns = [
                'id',
                'company_id',
                'row_id',
                'code',
                'value',
                'created_by',
                'created_at',
                'updated_by',
                'updated_at',
                'import_data_id',
                'data_type',
            ];
            $colInsert = $columns;
            unset($colInsert[0]);

            $queries = [
                'replace'   => [],
                'insert'    => []
            ];

            foreach ($items as $item) {
                $user_id = (int) $item[0];

                if ($replaceData === true) {
                    $row = $rows->filter(function ($row) use ($user_id) {
                        return (($row->code === 'user_id') && ($row->value == $user_id)) ? $row : null;
                    })->first();

                    if ($row->count() > 0) {
                        $row_id = $row->row_id;

                        $rowData = $rows->filter(function ($row) use ($row_id) {
                            return in_array($row->row_id, [$row_id]) ? $row : null;
                        })->toArray();

                        $queries['replace'][] = $rowData;
                    }
                } else {
                    $itemsArray = [];
                    for ($i = 0; $i < sizeof($codes); $i++) {
                        if (in_array($codes[$i], ['user_id', 'is_login'])) {
                            $itemsArray[$codes[$i]] = (int) $item[$i];
                        } else {
                            $itemsArray[$codes[$i]] = $item[$i];
                        }
                    }

                    $queries['insert'][] = $itemsArray;
                }
            }

            $this->info("Generated columns & values");
            $this->comment("Generating SQL for import");

            $disk = \Storage::disk('public');
            $importFile = 'import.sql';

            foreach ($queries as $key => $query) {
                if (($key === 'replace') && !empty($query)) {
                    //$sql .= "REPLACE INTO attribute_data(".implode(',', $columns).") VALUES";
                    $sqlItems = [];
                    foreach ($query as $item) {
                        foreach ($item as $value) {
                            $sqlItem = [];
                            foreach ($value as $k => $v) {
                                if ($k == 'updated_at') {
                                    $sqlItem[] = "'".Carbon::now()->toDateTimeString()."'";
                                } else {
                                    $sqlItem[] = "'{$v}'";
                                }
                            }

                            $sqlItems[] = '('.implode(',', $sqlItem).')';
                        }
                    }

                    $chunk = 200;
                    $size = ceil(sizeof($sqlItems)/$chunk);
                    for ($i = 0; $i <= $size; $i++) {
                        $start = $i*$chunk;
                        $sqlChunk = array_slice($sqlItems, $start, $chunk);

                        if (!empty($sqlChunk)) {
                            $sqlToChunk = "REPLACE INTO attribute_data(".implode(',', $columns).") VALUES" .
                                implode(',', $sqlChunk).";";

                            $disk->append($importFile, $sqlToChunk);
                        }
                    }
                } elseif (($key === 'insert') && !empty($query)) {
                    $counter = 0;
                    $sqlItems = [];
                    $row_id = (int) ceil(microtime(true)*100*20);
                    foreach ($query as $index => $item) {
                        $counter++;
                        foreach ($item as $k => $v) {
                            $sqlItems[] = "('{$company}', '{$row_id}', '{$k}', '{$v}', '1', '{$now->toDateTimeString()}', '1', '{$now->toDateTimeString()}', '0', 'user')";
                        }

                        $row_id += 1;
                    }

                    $chunk = 200;
                    $size = ceil(sizeof($sqlItems)/$chunk);
                    for ($i = 0; $i <= $size; $i++) {
                        $start = $i*$chunk;
                        $sqlChunk = array_slice($sqlItems, $start, $chunk);

                        if (!empty($sqlChunk)) {
                            $sqlToChunk = "INSERT INTO attribute_data(".implode(',', $colInsert).") VALUES" .
                                implode(',', $sqlChunk).";";

                            $disk->append($importFile, $sqlToChunk);
                        }
                    }
                }
            }

            $this->info("Generated SQL for import");

            $this->comment("Importing SQL");

            $default = config('database.default');
            $connection = config('database.connections.'.$default);

            RunExternalCommand::run("/usr/bin/mysql -u {$connection['username']} -p{$connection['password']} -h {$connection['host']} -P {$connection['port']} {$connection['database']} < " . storage_path('app/public/import.sql'));

            $this->comment("Imported SQL");

            $currentTime = Carbon::now();
            $timeTaken = $currentTime->diffInSeconds($now);

            $this->comment("Time taken for import: {$timeTaken} seconds");
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_REQUIRED, 'Company ID');
        $this->addOption('file', null, InputOption::VALUE_REQUIRED, 'File to be imported from storage/app/public folder');
    }
}
