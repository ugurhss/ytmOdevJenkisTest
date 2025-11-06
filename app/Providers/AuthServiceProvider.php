<?php

namespace App\Providers;

use App\Models\Group;
use App\Models\GroupAnnouncement;
use App\Policies\GroupAnnouncementPolicy;
use App\Policies\GroupPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Group::class => GroupPolicy::class,
        GroupAnnouncement::class => GroupAnnouncementPolicy::class,

    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
