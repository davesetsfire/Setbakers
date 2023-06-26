<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'propshop_main');
set('default_stage', 'staging');
set('keep_releases', 10);
set('default_timeout', 6000);
// Project repository
set('repository', 'git@bitbucket.org:zetify/propshop.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
add('shared_files', [
    '.env'
]);

add('shared_dirs', [
    'storage'
]);

// Writable dirs by web server 
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'storage/fonts',
]);


// Hosts
host('staging')
        ->stage('staging')
        ->hostname('164.52.202.69')
        ->user('smsgo_main')
        ->identityFile('~/.ssh/deployerkey')
        ->set('branch', 'master')
        ->set('deploy_path', '~/{{application}}');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

/**
 * Upload .env.production file as .env
 */
task('environment', function () {
    upload('.env.{{stage}}', '{{release_path}}/.env');
})->desc('Environment setup');


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:cache:clear',
    'artisan:optimize',
    'environment',
    'uresources_symlink',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
task('uresources_symlink', function() {
    run("cd {{release_path}}/public && ln -sfn {{release_path}}/storage/app/public storage");
})->desc('Creating symlink for uresources');
