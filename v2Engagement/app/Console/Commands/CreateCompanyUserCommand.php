<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateCompanyUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new company account';

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
            $data['company_key'] = $this->ask("Please specify the company account key" , str_random(50));
            $data['name'] = $this->ask("Please specify the company account name" , "Company Engagement");
            $data['email'] = $this->ask("Please specify the company account email address" , "company@engagement.com");
            $data['password'] = $this->ask("Please specify the company account password" , "123456");

            $data['password'] = bcrypt($data['password']);

            $company = \App\User::create($data);

            $role = config('laravel-permission.models.role');
            $companyRole = $role::where('name', 'COMPANY')->firstOrFail();

            $company->assignRole($companyRole);

            $this->info("Company {$company->name} has been created successfully!");
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
