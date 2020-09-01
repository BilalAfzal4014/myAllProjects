<?php

namespace App\Console\Commands;

use App\Components\RunExternalCommand;
use Illuminate\Console\Command;

class SetupApplicationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to setup admin & company user and import initial data.';

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
            // Read setup .env file content
            $content = file_get_contents(
                base_path('.env.setup')
            );

            $setup_app = [];
            $drivers = ["mysql", "pgsql", "sqlite", "sqlsrv"];

            $setup_app['APPNAME'] = $this->ask("Please specify the application name?");
            $setup_app['APPURL'] = $this->ask("What is the application URL?");
            $setup_app['APPKEY'] = 'base64:'.base64_encode(random_bytes(
                config('app.cipher') == 'AES-128-CBC' ? 16 : 32
            ));
            $app_url = $setup_app['APPURL'];

            $setup_app['DBDRIVER'] = $this->ask("Please specify the database driver. Should be one of these [".implode(',', $drivers)."]", "mysql");
            if (!in_array($setup_app['DBDRIVER'], $drivers)) {
                throw new \Exception("Invalid database driver specified. Should be one of these ".\GuzzleHttp\json_encode($drivers));
            }

            $setup_app['DBHOST'] = $this->ask("Please specify the database host url" , "127.0.0.1");
            $setup_app['DBPORT'] = $this->ask("Please specify the port for database host" , 3306);
            $setup_app['DBNAME'] = $this->ask("Please specify the database name");
            $setup_app['DBUSER'] = $this->ask("Please specify the database username");
            $setup_app['DBPASSWORD'] = $this->ask("Please specify the database password");

            $setup_app['MAILHOST'] = $this->ask("Please specify the SMTP Mail Host URL", "email-smtp.us-east-1.amazonaws.com");
            $setup_app['MAILPORT'] = $this->ask("Please specify the SMTP Mail Host Port", 587);
            $setup_app['MAILUSER'] = $this->ask("Please specify the SMTP Mail Host Username", "AKIAJRZAEJN6W7DTSZVQ");
            $setup_app['MAILPASSWORD'] = $this->ask("Please specify the SMTP Mail Host Password", "AhG63LHZXZV84DFoOmjcaJLXeuf7pmucFIPdMd6uIvx4");
            $setup_app['MAILENCRYPTION'] = $this->ask("Please specify the SMTP Mail Host Encryption", "tls");
            $setup_app['MAILFROM'] = $this->ask("Please specify the default sender email", "developer@rebeltechnology.io");
            $setup_app['MAILSENDER'] = $this->ask("Please specify the default sender name", "Engagement Platform Support");

            $setup_app['admin']['name'] = $this->ask("Please specify the admin account name" , "Admin Engagement");
            $setup_app['admin']['email'] = $this->ask("Please specify the admin account email address" , "admin@engagement.com");
            $setup_app['admin']['password'] = $this->ask("Please specify the admin account password" , "123456");

            $setup_app['company']['company_key'] = $this->ask("Please specify the company account key" , "ZbfvSyyTYH1slSQrUrXebWtogo5SWtMI9ibjiw65T5m4elPNIM");
            $setup_app['company']['name'] = $this->ask("Please specify the company account name" , "Company Engagement");
            $setup_app['company']['email'] = $this->ask("Please specify the company account email address" , "company@engagement.com");
            $setup_app['company']['password'] = $this->ask("Please specify the company account password" , "123456");

            // Parse & replace input config data.
            $this->comment(PHP_EOL . "Setting application configuration" . PHP_EOL);
            foreach ($setup_app as $key => $value) {
                if (!in_array($key, ['admin', 'company'])) {
                    $content = str_replace($key, $value, $content);
                    unset($setup_app[$key]);
                }
            }

            // Create default .env file
            $this->comment(PHP_EOL . "Creating .env file for configuration" . PHP_EOL);
            RunExternalCommand::run("cp .env.setup .env");
            file_put_contents(
                base_path('.env'),
                $content
            );
            $this->info(PHP_EOL . "Application configuration has been setup" . PHP_EOL);

            $this->comment(PHP_EOL . "Setting application cache" . PHP_EOL);
            $this->call("config:cache");
            $this->info(PHP_EOL . "Application cache has been generated." . PHP_EOL);

            // Run database migrations.
            $this->comment(PHP_EOL . "Running database migrations" . PHP_EOL);
            $this->call("migrate:refresh", ['--force']);
            $this->info(PHP_EOL . "All migrations have been run successfully" . PHP_EOL);
            
            \Cache::forever('setup_app', \GuzzleHttp\json_encode($setup_app));

            $this->comment(PHP_EOL . "Seeding the database with default data" . PHP_EOL);
            $this->call("db:seed");
            $this->info(PHP_EOL . "Admin & Company account has been created with default data." . PHP_EOL);

            $this->comment(PHP_EOL . "Updating composer packages" . PHP_EOL);
            RunExternalCommand::run("composer update");
            RunExternalCommand::run("composer dump-autoload");
            $this->info(PHP_EOL . "Composer packages have been updated." . PHP_EOL);

            $this->info(PHP_EOL . "Application has been setup at url {$app_url}." . PHP_EOL);
        } catch (\Exception $exception) {
            $this->error( PHP_EOL . "Unable to setup application. Following error occurred: " . PHP_EOL . $exception->getMessage());
        }
    }
}
