<?php

namespace Deployer;

require 'vendor/deployer/deployer/recipe/symfony3.php';

// Project name
set('application', 'phprum');

// Project repository
set('repository', 'https://github.com/mieszkomalawski/phprum.git');
set('writable_mode', 'chmod');
//set('symfony_env', 'dev');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
add('shared_files', ['app/config/parameters.yml']);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

localhost()
    ->set('deploy_path', '/data/www/private/phprum-prod');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

task('database:fixtures', function () {
    run('cd {{release_path}} && php bin/console doctrine:fixtures:load --env=prod');
})->once();

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');
after('database:migrate', 'database:fixtures');

