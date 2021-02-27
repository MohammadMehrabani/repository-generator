<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Directories
    |--------------------------------------------------------------------------
    |
    | The default directory structure
    |
    */

    'repository_directory' => app_path('Repositories/Eloquent/'),
    'interface_directory' => app_path('Repositories/Interfaces/'),
    'model_directory' => app_path('Models/'),
    'provider_directory' => app_path('Providers/'),

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    | The namespace of repository, interface, models and provider
    |
    */

    'repository_namespace' => 'App\Repositories\Eloquent',
    'interface_namespace' => 'App\Repositories\Interfaces',
    'model_namespace' => 'App\Models',
    'provider_namespace' => 'App\Providers',

    /*
    |--------------------------------------------------------------------------
    | Main Repository File
    |--------------------------------------------------------------------------
    |
    | The main repository class, other repositories will be extended from this
    |
    | If you're working with your customized repository file
    | You should change these values like below,
    |
    | 'main_repository_file' => 'CustomFile.php'
    | 'main_repository_class' => 'App\Custom\Repository:class'
    */

    // Only file name of the file because full path can cause errors.
    // We're gonna use "repository_directory" config value for it.
    'main_repository_file' => 'Repository.php',

    // Class name as string
    'main_repository_class' => \MohammadMehrabani\RepositoryGenerator\Repository::class,

    /*
    |--------------------------------------------------------------------------
    | Main Interface File
    |--------------------------------------------------------------------------
    |
    | The main interface class, other interfaces will be extended from this
    */

    'main_interface_file' => 'RepositoryInterface.php',
    'main_interface_class' => \MohammadMehrabani\RepositoryGenerator\RepositoryInterface::class,

    /*
    |--------------------------------------------------------------------------
    | Service Provider File
    |--------------------------------------------------------------------------
    |
    | The Repository Service Provider file
    | for binding interfaces to implementations automatically
    |
    */

    'service_provider_file' => 'RepositoryServiceProvider.php',
    'service_provider_class' => App\Providers\RepositoryServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Active Scope Configuration (Optional)
    |--------------------------------------------------------------------------
    |
    | Similar to Eloquent's scopes but global, E.g. Method::active()->get();
    | The database column which contains integer or bool data to filter.
    | Most projects need it but of course, you don't have to use it
    */

    'active_column' => 'active',

];
