<?php

// MUST RUN AS ROOT
// To execute, run this command in terminal: php create_virdo.php domain

try {
	if (count($argv) != 2) {
		throw new Exception("Invalid count of arguments sent to the script");
	}

	$domain_name = $argv[1];
	if (preg_match('/[^a-zA-Z0-9\-]+/', $domain_name) === 1) {
		throw new Exception("Invalid characters given in domain name: {$domain_name}");
	}

	// must change this according to your need
	$server_name = "{$domain_name}.mrk";
	$doc_root = "/var/www/html/{$server_name}";

	// optional, should work generally
	$server_ip = "127.0.0.1";
	$admin_email = "webmaster@localhost";

	$vhost = "<VirtualHost *:80>\n";
	$vhost .= "\tServerAdmin {$admin_email}\n";
	$vhost .= "\tDocumentRoot {$doc_root}\n";
	$vhost .= "\tServerName {$server_name}\n";
	$vhost .= "\tErrorLog ".'${APACHE_LOG_DIR}'."/{$server_name}-error_log\n";
	$vhost .= "\tCustomLog ".'${APACHE_LOG_DIR}'."/{$server_name}-access_log common\n";
	$vhost .= "</VirtualHost>";

	$httpd_conf = "/etc/apache2/sites-available/{$server_name}.conf";
	if (file_exists($httpd_conf)) {
		throw new Exception("conf file already exists: {$httpd_conf}");
	}

	$fp = fopen($httpd_conf, 'w');
	fwrite($fp, "\n".$vhost."\n");
	fclose($fp);

	echo "SUCCESS: Virtual domain creation".PHP_EOL;

	$hosts = "/etc/hosts";
	$server_data = "{$server_ip}\t{$server_name}\twww.{$server_name}";
	$fp = fopen($hosts, 'a');
	fwrite($fp, "\n".$server_data."\n");
	fclose($fp);

	echo "SUCCESS: Domain added to hosts list".PHP_EOL;

	// if doc_root does not exists then create it
	if(!is_dir($doc_root)) {
		exec("mkdir {$doc_root}");
	}

	// enable the site
	exec("cd /etc/apache2/sites-available/");
	exec("a2ensite {$server_name}.conf");

	// restart the apache
	exec("service apache2 restart");

	echo "SUCCESS: Apache restarted".PHP_EOL;

	echo "SUCCESS: All operation completed successfully".PHP_EOL;
} catch (Exception $e) {
	echo "ERROR: ".$e->getMessage().PHP_EOL;
}
