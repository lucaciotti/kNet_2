@servers(['kNet' => 'ced@213.152.198.50', 'kuantica' => 'ced@213.152.198.49'])

@setup
  $repo = 'https://github.com/lucaciotti/kNet.app.git';
  $release_dir = '/home/ced/releases';
  $app_dir = '/var/www/html/kNet/';
  $release = 'release_' . date('YmdHis');
@endsetup

@macro('deploy-dev', ['on' => 'kuantica'])
    fetch_repo_dev
    run_composer_dev
    update_permissions
    update_symlinks
    patch_Torann_Registry
    clear_all_data
@endmacro

@macro('deploy-master', ['on' => 'kNet'])
    fetch_repo_master
    run_composer_master
    update_permissions
    update_symlinks
    patch_Torann_Registry
    clear_all_data
@endmacro

@task('fetch_repo_master')
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{ $release_dir }};
    git clone -b master --depth=1 {{ $repo }} {{ $release }};
@endtask

@task('fetch_repo_dev')
    [ -d {{ $release_dir }} ] || mkdir {{ $release_dir }};
    cd {{ $release_dir }};
    git clone -b dev --depth=1 {{ $repo }} {{ $release }};
@endtask


@task('run_composer_master')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist --no-scripts;
    php artisan clear-compiled --env=production;
    php artisan optimize --env=production;
@endtask

@task('run_composer_dev')
    cd {{ $release_dir }}/{{ $release }};
    composer install --prefer-dist;
@endtask


@task('update_permissions')
    cd {{ $release_dir }};
    chgrp -R www-data {{ $release }};
    chmod -R ug+rwx {{ $release }};
@endtask


@task('update_symlinks')
    ln -nfs {{ $release_dir }}/{{ $release }} {{ $app_dir }};
    chgrp -h www-data {{ $app_dir }};

    cd {{ $release_dir }}/{{ $release }};
    ln -nfs ../../.env .env;
    chgrp -h www-data .env;

    {{-- rm -r {{ $release_dir }}/{{ $release }}/storage/logs;
    cd {{ $release_dir }}/{{ $release }}/storage;
    ln -nfs ../../../logs logs;
    chgrp -h www-data logs; --}}

    sudo service php5-fpm reload;
@endtask

@task('patch_Torann_Registry')
    yes | cp -irf {{ $release_dir }}/{{ $release }}/myPackages/* {{ $release_dir }}/{{ $release }}/vendor
@endtask

@task('clear_all_data')
    cd {{ $release_dir }}/{{ $release }};
    truncate -s 0 storage/logs/laravel.log;
    php artisan cache:clear;
    php artisan clear-compiled;
    sudo chmod -R 777 storage/ -R;
    composer dump-autoload;
@endtask

@task('snappyPDF_dependencies')
    cp vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64 /usr/local/bin/
    cp vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64 /usr/local/bin/
    chmod +x /usr/local/bin/wkhtmltoimage-amd64 
    chmod +x /usr/local/bin/wkhtmltopdf-amd64
@endtask

{{--  Solo per test  --}}