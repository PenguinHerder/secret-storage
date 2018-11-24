[rpcinterface:supervisor]
supervisor.rpcinterface_factory = supervisor.rpcinterface:make_main_rpcinterface

[supervisord]
user={{ $g('user') }}
logfile=%(here)s/../storage/logs/supervisord.log
logfile_backups=3
pidfile=%(here)s/../storage/logs/supervisord.pid

[unix_http_server]
file={{ $g('ctl_server') }}

[supervisorctl]
serverurl={{ $g('ctl_protocol').$g('ctl_server') }}

[program:default_queue]
command={{ $g('php_app') }} {{ $g('root') }}/artisan queue:work --sleep=5
autostart=true
autorestart=true
numprocs=3
redirect_stderr=true
stdout_logfile={{ $g('root') }}/storage/logs/worker_%(program_name)s.log
stdout_logfile_maxbytes=50MB
stdout_logfile_backups=2
