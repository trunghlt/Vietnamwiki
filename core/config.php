<?php
//ini_set('log_erros', 'On');
//ini_set('error_log', '/usr/local/apache/logs/error_log');
/*
 * To install:
 *   1. Copy this file to config.php
 *   2. Follow the instructions below to make the app work.
 */

/*
 * Enter your callback URL here. That's the location where index.php
 * resides. Make sure it's your exact root - facebook.com
 * and www.facebook.com are different.
 */
$callback_url     = "http://www.vietnamwiki.net/";

/*
 * Get the API key and secret from http://facebook.com/developers
 * Note that each callback URL needs its own app id.
 *
 * Set the callback URL in your developer app to match the one you chose above.
 * This is important so that the Javascript cross-domain library works correctly.
 *
 */
$api_key         = 'd549b1b6fb373396bbc4768229203992';
$api_secret      = '9e6dc1bb29f132192e50b3cdab909e21';

// This is the root of the facebook site you'll be hitting. In production, this will be facebook.com
$base_fb_url     = 'connect.facebook.com';

/*
 * The Run Around has a single feed story, which is displayed when you add a run.
 * The feed story template needs to be registered with your app_key, and then just passed
 * at run time. To register the feed bundle for your app, visit:
 *
 * www.yourapp.com/register_feed_forms.php
 *
 * Then copy/paste the resulting feed bundle ID here.
 */
$feed_bundle_id  = 99999999;
$MEMCACHED_PORT = 11211;
$HOST_SOLR = "109.123.66.160";
$PORT_SOLR = "8983";
$SERVER_SOLR = "solr";
?>
