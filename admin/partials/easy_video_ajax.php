<?php 

$type = $_POST['type'];

if ( $type == 'getYtData' ){

    $yt_api = get_option( 'youtube_api_key' );
    $channel_id = $_POST['channel_id'];
    $yt_username = $_POST['yt_username'];
    $pageId = $_POST['pageId'];

    $output = '';
    if ( $yt_api == '') {

        echo json_encode(array(
            'result'    => '0'
        ));

    }else{

        if ( !empty($channel_id) ){
            
            $get_data = yt_get_channel_id($channel_id, $pageId);
            if ( !empty($get_data['items']) ) {

                if ( array_key_exists( 'nextPageToken', $get_data ) ) {
                    $output .= '<hr>';
                    $output .= '<button class="button button-primary ev_import_all">Import All</button>';
                    $output .= '<img id="ev_loader_all" src="' . EV_LOADER . '" style="display:none;">';
                    $output .= '<table class="ev_video_sample_table">';
                    $output .= '<tr>
                    <th style="width: 20%;">Video Title</th>
                    <th style="width: 30%;">Thumbnail</th>
                    <th style="width: 20%;">Publish Date</th>
                    <th>Action</th>
                    </tr>';
    
                    foreach($get_data['items'] as $data){
                        $output .= ev_get_yt_api_data($data);
                    }
                    
                }elseif ( array_key_exists( 'prevPageToken', $get_data ) ){
                    
                    $output .= '<table class="ev_video_sample_table">';
                    $output .= '<tr>
                    <th style="width: 20%;">Video Title</th>
                    <th style="width: 30%;">Thumbnail</th>
                    <th style="width: 20%;">Publish Date</th>
                    <th>Action</th>
                    </tr>';
    
                    foreach($get_data['items'] as $data){
                        $output .= ev_get_yt_api_data($data);
                    }
                    
                }
                $output .= '</table>';
            }

            if ( !empty($get_data['items']) ) {
                $output .= '<div class="ev_pagination">';
                if ( array_key_exists( 'nextPageToken', $get_data ) ) {
                    if ( array_key_exists( 'nextPageToken', $get_data ) && array_key_exists( 'prevPageToken', $get_data ) ) {
                        $output .= '<button class="ev_prev_btn button button-primary" data-id="' . $get_data['prevPageToken'] . '">Previous</button><button class="ev_next_btn button button-primary" data-id="' . $get_data['nextPageToken'] . '">Next</button>';
                    }elseif ( array_key_exists( 'nextPageToken', $get_data ) ) {
                        $output .= '<button class="ev_next_btn button button-primary" data-id="' . $get_data['nextPageToken'] . '">Next</button>';
                    }
                }else{
                    $output .= '<button class="ev_prev_btn button button-primary" data-id="' . $get_data['prevPageToken'] . '">Previous</button>';
                }
                $output .= '</div>';
            }
            
            
        } else {
            
            // Check with username
            $getDataFromUser = yt_get_user($yt_username);
            $userChannelId = $getDataFromUser['items'][0]['id'];
            $get_data = yt_get_channel_id($userChannelId, $pageId);
            
            if ( !empty($get_data['items']) ) {

                if ( array_key_exists( 'nextPageToken', $get_data ) ) {
                    $output .= '<hr>';
                    $output .= '<button class="button button-primary ev_import_all">Import All</button>';
                    $output .= '<img id="ev_loader_all" src="' . EV_LOADER . '" style="display:none;">';
                    $output .= '<table class="ev_video_sample_table">';
                    $output .= '<tr>
                    <th style="width: 20%;">Video Title</th>
                    <th style="width: 30%;">Thumbnail</th>
                    <th style="width: 20%;">Publish Date</th>
                    <th>Action</th>
                    </tr>';
    
                    foreach($get_data['items'] as $data){
                        $output .= ev_get_yt_api_data($data);
                    }
                    
                }elseif ( array_key_exists( 'prevPageToken', $get_data ) ){
                    
                    $output .= '<table class="ev_video_sample_table">';
                    $output .= '<tr>
                    <th style="width: 20%;">Video Title</th>
                    <th style="width: 30%;">Thumbnail</th>
                    <th style="width: 20%;">Publish Date</th>
                    <th>Action</th>
                    </tr>';
    
                    foreach($get_data['items'] as $data){
                        $output .= ev_get_yt_api_data($data);
                    }
                    
                }
                $output .= '</table>';
            }

            if ( !empty($get_data['items']) ) {
                $output .= '<div class="ev_pagination">';
                if ( array_key_exists( 'nextPageToken', $get_data ) ) {
                    if ( array_key_exists( 'nextPageToken', $get_data ) && array_key_exists( 'prevPageToken', $get_data ) ) {
                        $output .= '<button class="ev_prev_btn button button-primary" data-id="' . $get_data['prevPageToken'] . '">Previous</button><button class="ev_next_btn button button-primary" data-id="' . $get_data['nextPageToken'] . '">Next</button>';
                    }elseif ( array_key_exists( 'nextPageToken', $get_data ) ) {
                        $output .= '<button class="ev_next_btn button button-primary" data-id="' . $get_data['nextPageToken'] . '">Next</button>';
                    }
                }else{
                    $output .= '<button class="ev_prev_btn button button-primary" data-id="' . $get_data['prevPageToken'] . '">Previous</button>';
                }
                $output .= '</div>';
            }

        }
    
        echo json_encode( array(
            'output'    => $output,
            'result'    => '1'
        ) );
    }


    wp_die();

}
elseif ( $type == 'importToPost' ) {
    
    $title = $_POST['title'];
    $videoId = $_POST['videoId'];
    $imageUrl = $_POST['imageUrl'];
    $imageName = $_POST['imageName'];

    // Get category
    $category = ev_get_yt_category($videoId);

    //Check if category already exists
    $cat_slug = get_term_by( 'slug', $category, 'video-categories' );
    $cat_ID = $cat_slug->term_id;

    //If it doesn't exist create new category
    if( $cat_ID == 0 || $cat_ID == '' ) {
        $cat_name = array('cat_name' => $category, 'taxonomy' => 'video-categories');
        $cat_ID = wp_insert_category($cat_name);
    }
    
    // Checking if post exists or not
    $checkPostExist = get_page_by_title($title, OBJECT, 'videos');
    if ( !$checkPostExist ) {
    
        $my_post = array(
            'post_type'     => 'videos',
            'post_title'    => $title,
            'post_content'  => '<iframe width="560" height="315" src="https://www.youtube.com/embed/'. $videoId .'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; allowfullscreen></iframe>',
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_category' => array($cat_ID),

        ); 
    
        $post_id = wp_insert_post( $my_post );
        
        $taxonomy = 'video-categories';
        wp_set_object_terms($post_id, array($cat_ID), $taxonomy); 
    
        // Add Featured Image to Post
        $image_url        = $imageUrl; // Define the image URL here
        $image_name       = $imageName;
        $upload_dir       = wp_upload_dir(); // Set upload folder
        $image_data       = file_get_contents($image_url); // Get image data
        $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
        $filename         = basename( $unique_file_name ); // Create image file name
    
        // Check folder permission and define file location
        if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
        } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
        }
    
        // Create the image  file on the server
        file_put_contents( $file, $image_data );
    
        // Check image file type
        $wp_filetype = wp_check_filetype( $filename, null );
    
        // Set attachment data
        $attachment = array(
            'post_type'         => 'videos',
            'post_mime_type'    => $wp_filetype['type'],
            'post_title'        => $title,
            'post_content'      => '',
            'post_status'       => 'inherit'
        );
    
        // Create the attachment
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    
        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');
    
        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    
        // Assign metadata to attachment
        wp_update_attachment_metadata( $attach_id, $attach_data );
    
        // And finally assign featured image to post
        set_post_thumbnail( $post_id, $attach_id );

        echo json_encode(array(
            'status'   => 1
        ));

    }else{

        echo json_encode(array(
            'status'   => 0
        ));

    }
    
    wp_die();
}
elseif ( $type == 'importAll' ) {
    
    $yt_api = get_option( 'youtube_api_key' );
    $channel_id = $_POST['channel_id'];
    $yt_username = $_POST['yt_username'];

    $output = '';
    if ( $yt_api == '') {

        echo json_encode(array(
            'result'    => '0'
        ));

    }else{

        if ( !empty($channel_id) ){
            
            $get_data = yt_get_channel_id($channel_id);
            importAllPostToVideo($get_data, $channel_id);

        } else {
            
            // Check with username
            $getDataFromUser = yt_get_user($yt_username);
            $userChannelId = $getDataFromUser['items'][0]['id'];
            $get_data = yt_get_channel_id($userChannelId);

            importAllPostToVideo($get_data, $userChannelId);

        }
    
        echo json_encode( array(
            'result'    => '1'
        ) );
    }
    wp_die();

}

?>