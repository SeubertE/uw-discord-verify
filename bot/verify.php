<?php
/*
ECE SAC Discord Verification
----------------------------------------------
This file doesn't need to be accesible to the public, you should
make sure that you have .htaccess set properly. This script
instant invites a Discord user, gives them a role, and changes
their nickname to REMOTE_USER. REMOTE_USER would be a UW students
NETID.
----------------------------------------------
Adapted from: https://gist.github.com/Jengas/ad128715cb4f73f5cde9c467edf64b00
*/

include('config.php');
session_start();

// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {
  
  // Exchange the auth code for a token
  $token = apiRequest($tokenURL, array(
    "grant_type" => "authorization_code",
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'redirect_uri' => $redirectURL,
    'code' => get('code'),
    'Accept: application/json'
  ));
  $_SESSION['access_token'] = $token->access_token;
  header('Location: ' . $_SERVER['PHP_SELF']);
}

if(session('access_token')) {
    //get userid
    $user = callApi('https://discord.com/api/users/@me', '', array('Authorization: Bearer ' . session('access_token'), 'Content-Type' => 'application/json'));

    //add them to the server!
    $putheader = array(
        'Content-Type: application/json',
        'Authorization: Bot ' . $botToken,
    );

    $putdata = array(
        "access_token" => session('access_token'),
        "nick" => $_SERVER['REMOTE_USER'],
        "roles" => array($role)
    );

    $rvaladdtoguild = callApi('https://discord.com/api/guilds/' . $guildId . '/members/' . $user->id, 'PUT', $putheader, $putdata);

    //send message to channel that user was verified
    $putdata = array(
      "content" => 'User ID:' . $user->id . 'verified with UW NETID' . $_SERVER['REMOTE_USER']
    );

    $rvalsendmessage = callApi('https://discord.com/api/channels/' . $channelId . '/messages', 'POST', $putheader, $putdata);

    //change nick name and role (if already in server)
    $patchheader = array(
      'Content-Type: application/json',
    );

    $rvalchangeandverify = callApi('https://discord.com/api/guilds/' . $guildId . '/members/' . $user->id, 'PATCH', $putheader, $putdata);

    //redirect after done
    //header('Location: ' . $postauthURL);

    //debugging
    print_r($channelId);
    print_r($rvalsendmessage);

    die();
} 

if(get('action')=='acceptedterms'){
  $params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => $redirectURL,
    'response_type' => 'code',
    'scope' => 'identify guilds guilds.join'
  );

  // Redirect the user to Discord's authorization page
  header('Location: https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params));
  die();
}

function apiRequest($url, $post=FALSE, $headers=array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  
    $response = curl_exec($ch);
    
    if($post){
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
  
    $headers[] = 'Accept: application/json';
  
    if(session('access_token')){
      $headers[] = 'Authorization: Bearer ' . session('access_token');
    }
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  
    $response = curl_exec($ch);
    return json_decode($response);
}

function callApi($url, $method = '', $headers = array(), $data = array()) {
    
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        case "PUT":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
          curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
          break;
        case "PATCH":
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
          curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}

function get($key, $default=NULL) {
  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}
?>
