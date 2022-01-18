<?php

function ev_get_yt_api_data($data = array()){

    $output = '';
    $publishDate = date('d-m-Y', strtotime($data['snippet']['publishedAt']));

    $output .= '<tr>';
    $output .= '<td>' . $data['snippet']['title'] . '</td>';
    $output .= '<td><img src="' . $data['snippet']['thumbnails']['medium']['url'] . '" width="150" /></td>';
    $output .= '<td>' . $publishDate . '</td>';
    $output .= '<td class="ev_action_td"><a href="https://www.youtube.com/watch?v=' . $data['id']['videoId'] . '" target="_blank">View</a><a href="javascript:void(0);" data-title="' . $data['snippet']['title'] . '" data-video="' . $data['id']['videoId'] . '" data-image="' . $data['snippet']['thumbnails']['high']['url'] . '" class="ev_import_to_video">Import to Videos</a><img id="ev_loader" src="' . EV_LOADER . '" style="display:none;"></td>';
    $output .= '</tr>';
    
    return $output;
}

function importAllPostToVideo($data = array(), $channelId){
    
    if ( !empty($data['items']) ) {

        foreach ($data['items'] as $datas) {
            
            $title = $datas['snippet']['title'];
            $videoId = $datas['id']['videoId'];
            $imageUrl = $datas['snippet']['thumbnails']['high']['url'];
            $imageName = pathinfo($imageUrl);
            $imageName = $imageName['filename'].'.'.$imageName['extension'];

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

            }

        }

        if ( array_key_exists( 'nextPageToken', $data ) ) {
            $get_data = yt_get_channel_id($channelId, $data['nextPageToken']);
            importAllPostToVideo($get_data, $channelId);
        }

    }

}


?>