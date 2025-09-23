<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\IssuerSettings' => 'App\Policies\IssuerSettingsPolicy',
        'App\Models\Heading' => 'App\Policies\HeadingPolicy',
        'App\Models\Issuer' => 'App\Policies\IssuerPolicy',
        'App\Models\OperativeSectionBeginning' => 'App\Policies\OperativeSectionBeginningPolicy',
        'App\Models\RedactaUser' => 'App\Policies\RedactaUserPolicy',
        'App\Models\Document' => 'App\Policies\DocumentPolicy',
        'App\Models\Anexo' => 'App\Policies\AnexoPolicy',
        'App\Models\DocumentSharedAccess' => 'App\Policies\DocumentSharedAccessPolicy',
        'App\Models\File' => 'App\Policies\FilePolicy',
        'App\Models\Group' => 'App\Policies\GroupPolicy',
        'App\Models\GroupMembership' => 'App\Policies\GroupMembershipPolicy',
        'App\Models\Stamp' => 'App\Policies\StampPolicy',
        'App\Models\SignupInvitation' => 'App\Policies\SignupInvitationPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
