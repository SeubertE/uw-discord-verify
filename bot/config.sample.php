<?php
/*
Sample config file for ECE SAC Verification Bot
----------------------------------------------------

Author: Eugene Seubert (seuberte@uw.edu)

----------------------------------------------------

redirectURL - URL provided to Discord to redirect to
guildID - Guild ID to add to
postauthURL - Where to redirect after verified (probably should be the guild)
role - Role to give user

*/
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

define('OAUTH2_CLIENT_ID', '');
define('OAUTH2_CLIENT_SECRET', '');

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$redirectURL = '';
$guildId = '';
$postauthURL = '';
$role = '';
$channelID ='';

?>