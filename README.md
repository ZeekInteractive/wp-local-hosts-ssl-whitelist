# WP Local Hosts SSL Whitelist

Disable WordPress's certificate verification selectively against local hosts that exist in /etc/hosts.

## Usage
Add the following to your `.env.php` file:
```php
'LOCAL_DEV' => true,
```

Or you can add the following constant to your `wp-config.php` file:
```php
define( 'LOCAL_DEV', true );
```