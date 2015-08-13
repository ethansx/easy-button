# easy-button

##What it is?
Easy Button on Magento Cache Management Page that allows you to flush all caches with one single click


##Flush the following cache storage:

 - Magento Cache
 - Magento Full Page Cache
 - APC
 - PHP-FPM
 - Redis
 - Cloudflare


##Usage:

Zero configuration for Magento Cache, Full Page Cache and APC. All you need to do is enable these modules seperately in system > configuration > GaugeInteractive

In PHP-FPM settings, you will have to select an OS type and enter PHP-FPM daemon name (usually php-fpm).

In Redis settings, you will need to configure host, port and db number for each instance. Each redis instance has to be configured in the following format delimited by "," and ended by ";". For example, host1, port, db; host2, port, db; phpredis must be installed on the server in order for this to work. https://github.com/phpredis/phpredis

In Cloudflare settings, you will have to enter your Cloudflare account email, domain and token.

Easybutton supports cronjob to flush cache on schedule.