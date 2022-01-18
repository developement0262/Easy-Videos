<?php settings_errors(); ?>
<h1>Easy Video Settings</h1>
<form method="post" action="options.php">
    <?php 
        settings_fields( 'ev_youtube_api_settings' );
        do_settings_sections( 'ev_youtube_api_settings') ;
    ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th>
                    <label for="youtube-api-key">YouTube API Key</label>
                </th>
                <td>
                    <input name="youtube_api_key" type="text" id="youtube_api_key" class="regular-text" value="<?php echo get_option('youtube_api_key') ?>" required="required">
                </td>
            </tr>
        </tbody>
    </table>
    <?php submit_button(); ?>
</form>