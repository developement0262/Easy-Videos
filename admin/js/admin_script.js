jQuery(document).ready(function(){

    jQuery('img#ev_loader_all').hide();

    jQuery(document).on('click', '#search_yt', function(){

        var channel_id = jQuery('#channel_id').val();
        var yt_username = jQuery('#yt_username').val();
        var pageId = '';

        if( channel_id == '' && yt_username == '' ){
            alert('Please add Channel ID or Username in below field to get videos');
            return false;
        }

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : ev_ajax_script.ajaxurl,
            data : {
                action: "ev_easy_video_ajax",
                type: "getYtData",
                channel_id: channel_id,
                yt_username: yt_username,
                pageId: pageId,
            },
            success : function( response ) {
                if ( response.result == 0 ) {
                    alert('Please add your YouTube API key');
                    return false;
                }else{
                    jQuery('#yt-data').html(response.output);
                }
            }
        });

    });

    // Next button click
    jQuery(document).on('click', '.ev_next_btn', function(){

        var pageId = jQuery(this).attr('data-id');
        var channel_id = jQuery('#channel_id').val();
        var yt_username = jQuery('#yt_username').val();

        if( channel_id == '' && yt_username == '' ){
            alert('Please add Channel ID or Username in below field to get videos');
            return false;
        }

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : ev_ajax_script.ajaxurl,
            data : {
                action: "ev_easy_video_ajax",
                type: "getYtData",
                channel_id: channel_id,
                yt_username: yt_username,
                pageId: pageId
            },
            success : function( response ) {
                if ( response.result == 0 ) {
                    alert('Please add your YouTube API key');
                    return false;
                }else{
                    jQuery('#yt-data').html(response.output);
                }
            }
        });
        
    });

    // Previous button click
    jQuery(document).on('click', '.ev_prev_btn', function(){

        var pageId = jQuery(this).attr('data-id');
        var channel_id = jQuery('#channel_id').val();
        var yt_username = jQuery('#yt_username').val();

        if( channel_id == '' && yt_username == '' ){
            alert('Please add Channel ID or Username in below field to get videos');
            return false;
        }

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : ev_ajax_script.ajaxurl,
            data : {
                action: "ev_easy_video_ajax",
                type: "getYtData",
                channel_id: channel_id,
                yt_username: yt_username,
                pageId: pageId
            },
            success : function( response ) {
                if ( response.result == 0 ) {
                    alert('Please add your YouTube API key');
                    return false;
                }else{
                    jQuery('#yt-data').html(response.output);
                }
            }
        });
        
    });

    jQuery(document).on('click', '.ev_import_to_video', function(){
        var obj = this;
        var title = jQuery(this).attr('data-title');
        var videoId = jQuery(this).attr('data-video');
        var image = jQuery(this).attr('data-image');

        var index = image.lastIndexOf("/") + 1;
        var filename = image.substr(index);

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : ev_ajax_script.ajaxurl,
            data : {
                action: "ev_easy_video_ajax",
                type: "importToPost",
                title: title,
                videoId: videoId,
                imageUrl: image,
                imageName: filename
            },
            beforeSend: function(){
                jQuery(obj).parent().find('img#ev_loader').show();
            },
            success : function( response ) {
                jQuery('img#ev_loader').hide();
                if ( response.status == 1 ) {
                    alert('Post Inserted Successfully!!');
                }else{
                    alert('Post is already exists!!');
                }
            }
        });

    });

    jQuery(document).on('click', '.ev_import_all', function(){

        var channel_id = jQuery('#channel_id').val();
        var yt_username = jQuery('#yt_username').val();

        if( channel_id == '' && yt_username == '' ){
            alert('Please add Channel ID or Username in below field to get videos');
            return false;
        }

        jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : ev_ajax_script.ajaxurl,
            data : {
                action: "ev_easy_video_ajax",
                type: "importAll",
                channel_id: channel_id,
                yt_username: yt_username,
            },
            beforeSend: function() {
                jQuery('img#ev_loader_all').show();
            },
            success : function( response ) {
                if ( response.result == 1 ) {
                    alert('All posts has been imported!!');
                    jQuery('img#ev_loader_all').hide();
                }
            }
        });

    });



});