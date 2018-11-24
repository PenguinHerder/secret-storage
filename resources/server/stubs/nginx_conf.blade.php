user {{ $g('user') }};
daemon {{ $g('daemon') }};

events {
    worker_connections 1024;
    use epoll;
}

http {
	error_log {{ $g('log_folder') }}/http_error.log {{ $g('log_level') }};
	access_log {{ $g('log_folder') }}/http_access.log;
	
	include {{ $g('mime_types') }};
	
	client_max_body_size {{ $g('max_upload') }};

	server {
		listen {{ $g('port') }};

		server_name local.storage;

		root {{ $g('root') }}/public/;
		index index.php;

		location / {
			try_files $uri $uri/ /index.php$is_args$args;
		}

		location ~ \.php$ {
			fastcgi_pass 127.0.0.1:{{ $g('fcgi_port') }};
			include {{ $g('fcgi_include') }};
			fastcgi_param REQUEST_METHOD $request_method;
			fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
			fastcgi_read_timeout 1800;
		}
	}
}
