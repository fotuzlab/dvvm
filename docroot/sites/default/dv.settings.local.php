<?php

/**
 * @file
 * Copy this file as settings.local.php.
 * Do not commit settings.local.php to git.
 */

// Set testing TRUE of FALSE to switch configuration between testing and development
// environments.
$testing = FALSE;
// Test remotely or locally.
$remote_testing = FALSE;
// If testing with remote database, set the name of the sandbox to connect to it.
// WARNING: DO NOT USE CRITICAL SANDBOXES LIKE PROXYPROD, DEVELOP for remote testing.
$sandbox = '';

/**
 * Do not modify anything below this line.
 */

// Settings for testing environment.
if (isset($testing) && $testing == TRUE) {
	if (isset($remote_testing) && $remote_testing == TRUE) {

		// Get VPC credentials
		$credentials = json_decode(file_get_contents('./sites/default/dv_credentials.json'))->mysql;

		$databases['default']['default'] = array (
		  'database' => $sandbox . '_dv',
		  'username' => $credentials->username,
		  'password' => $credentials->password,
		  'prefix' => '',
		  'host' => $credentials->host,
		  'port' => $credentials->port,
		  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
		  'driver' => 'mysql',
		);

		// Show a notification about branch and sandbox.
		$current_branch = str_replace('ref: refs/heads/', '', file_get_contents('../.git/HEAD'));
		$message = '
		  <div id="block-emergencymessages">
			   <div>
			      <section class="c-emergency-messages c-emergency-messages--em_info is-visible">
			         <div class="c-emergency-messages__padding">
			            <div class="container">
			               <div class="row">
			                  <div class="col-sm-12 col-md-8 col-lg-9">
			                     <div class="c-emergency-messages__text">
			                        <p>You are remote testing <b>' . $sandbox . '</b> and are on <i>' . $current_branch . '</i> branch.</p>
			                     </div>
			                  </div>
			               </div>
			            </div>
			         </div>
			      </section>
			   </div>
			</div>
		';
		// @TODO: Improve the way to show the message.
		print($message);

	}
	else {
		$databases['default']['default'] = array (
		  'database' => $default_db['database'],
		  'username' => $default_db['username'],
		  'password' => $default_db['password'],
		  'prefix' => $default_db['prefix'],
		  'host' => $default_db['host'],
		  'port' => $default_db['port'],
		  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
		  'driver' => 'mysql',
		);

	}

	$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/testing.services.yml';

	$memcache_exists = class_exists('Memcache', FALSE);
	$memcached_exists = class_exists('Memcached', FALSE);
	if ($memcache_exists || $memcached_exists) {
	  // Enable memcache.
	  $settings['cache']['default'] = 'cache.backend.memcache';
	  $settings['cache']['bins']['render'] = 'cache.backend.memcache';

	  $class_loader->addPsr4('Drupal\\memcache\\', 'modules/contrib/memcache/src');

	  // Define custom bootstrap container definition to use Memcache for cache.container.
	  $settings['bootstrap_container_definition'] = [
	    'parameters' => [],
	    'services' => [
	      # Dependencies.
	      'settings' => [
	        'class' => 'Drupal\Core\Site\Settings',
	        'factory' => 'Drupal\Core\Site\Settings::getInstance',
	      ],
	      'memcache.settings' => [
	        'class' => 'Drupal\memcache\MemcacheSettings',
	        'arguments' => ['@settings'],
	      ],
	      'memcache.factory' => [
	        'class' => 'Drupal\memcache\Driver\MemcacheDriverFactory',
	        'arguments' => ['@memcache.settings'],
	      ],
	      'memcache.timestamp.invalidator.bin' => [
	        'class' => 'Drupal\memcache\Invalidator\MemcacheTimestampInvalidator',
	        # Adjust tolerance factor as appropriate when not running memcache on localhost.
	        'arguments' => ['@memcache.factory', 'memcache_bin_timestamps', 0.001],
	      ],
	      'memcache.timestamp.invalidator.tag' => [
	        'class' => 'Drupal\memcache\Invalidator\MemcacheTimestampInvalidator',
	        # Remember to update your main service definition in sync with this!
	        # Adjust tolerance factor as appropriate when not running memcache on localhost.
	        'arguments' => ['@memcache.factory', 'memcache_tag_timestamps', 0.001],
	      ],
	      'memcache.backend.cache.container' => [
	        'class' => 'Drupal\memcache\DrupalMemcacheInterface',
	        'factory' => ['@memcache.factory', 'get'],
	        # Actual cache bin to use for the container cache.
	        'arguments' => ['container'],
	      ],
	      # Define a custom cache tags invalidator for the bootstrap container.
	      'cache_tags_provider.container' => [
	        'class' => 'Drupal\memcache\Cache\TimestampCacheTagsChecksum',
	        'arguments' => ['@memcache.timestamp.invalidator.tag'],
	      ],
	      'cache.container' => [
	        'class' => 'Drupal\memcache\MemcacheBackend',
	        'arguments' => ['container', '@memcache.backend.cache.container', '@cache_tags_provider.container', '@memcache.timestamp.invalidator.bin'],
	      ],
	    ],
	  ];
	}
}
// Settings for development environment. Also derves as default.
else {
    // Set local database.
    $databases['default']['default'] = array (
	  'database' => 'drupal',
	  'username' => 'drupal',
	  'password' => 'drupal',
	  'prefix' => '',
	  'host' => 'localhost',
	  'port' => 3306,
	  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
	  'driver' => 'mysql',
	);

	$config['system.logging']['error_level'] = 'verbose';

	$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

	// Disable CSS and JS aggregation.
	$config['system.performance']['css']['preprocess'] = FALSE;
	$config['system.performance']['js']['preprocess'] = FALSE;

	// Disable render cache.
	$settings['cache']['bins']['render'] = 'cache.backend.null';
	$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

	$settings['rebuild_access'] = TRUE;
}

// Set menu indicator.
$settings['simple_environment_indicator'] = '#339900';