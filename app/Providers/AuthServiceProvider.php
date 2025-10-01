<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\UserPolicy;
use App\Repositories\SysRoleRepository;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Cek dulu apakah tabel task_data sudah ada
        if (Schema::hasTable('task_data')) {
            $taskData = \DB::table('task_data')->pluck('task_data_name');

            foreach ($taskData as $task) {
                Gate::define($task, function ($user, $module) use ($task) {
                    $roleRepository = new SysRoleRepository;
                    return $roleRepository->getByModuleTask($module, $task, $user->group_id) ? true : false;
                });
            }
        }

        \Illuminate\Support\Facades\Auth::provider('customuserprovider', function ($app, array $config) {
            return new CustomUserProvider($app['hash'], $config['model']);
        });
    }
}
