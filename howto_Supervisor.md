[program:knet-jobs]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/kdev/current/artisan queue:work redis --queue=jobs --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=2
redirect_stderr=true
stderr_logfile=/var/www/html/kdev/shared/storage/logs/importFiles.err.log
stdout_logfile=/var/www/html/kdev/shared/storage/logs/importFiles.log
stopwaitsecs=3600

[program:knet-emails]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/kdev/current/artisan queue:work redis --queue=emails --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=2
redirect_stderr=true
stderr_logfile=/var/www/html/kdev/shared/storage/logs/importFiles.err.log
stdout_logfile=/var/www/html/kdev/shared/storage/logs/importFiles.log
stopwaitsecs=3600

# crontab per scheduler
* * * * * cd /var/www/html/kdev/current && php artisan schedule:run >> /dev/null 2>&1

