<?php

/*
* If Channel ID
*/
function yt_get_channel_id($channel_id, $nextPageToken=''){

    $yt_api_key = get_option( 'youtube_api_key' );
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/search?key=' . $yt_api_key . '&part=snippet&maxResults=10&order=date&channelId=' . $channel_id . '&pageToken=' . $nextPageToken,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    return json_decode($response, TRUE);

}

/*
* If Username
*/
function yt_get_user($username){

  $yt_api_key = get_option( 'youtube_api_key' );
    
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/channels?key=' . $yt_api_key . '&part=snippet&maxResults=10&forUsername=' . $username,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  return json_decode($response, TRUE);

}

function ev_get_yt_category($videoId){

  $yt_api_key = get_option( 'youtube_api_key' );
    
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?key=' . $yt_api_key . '&part=snippet&id=' . $videoId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  $response = json_decode($response, TRUE);

  $categoryId = $response['items'][0]['snippet']['categoryId'];
  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videoCategories?part=snippet&key=' . $yt_api_key . '&id=' . $categoryId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  $cat_response = json_decode($response, TRUE);
  return $cat_response['items'][0]['snippet']['title'];

}



?>