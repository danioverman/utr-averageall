<div class="wrap">
  <h2><?php _e('Uptime Robot Settings', 'urtbr') ?></h2>
<form method="post" action="options.php">
  <?php
    settings_fields( 'utrbr-settings' );
      if  ( get_option('postfield-legend') == '' ) {
        update_option('postfield-legend',__('Post Content','utrbr'));
    }
  ?>
  <div class="postbox">
  <h3><?php _e('API Settings', 'utrbr') ?></h3>
    <div class="inside">
      <table class="form-table">
          <tr valign="top">
            <th scope="row"><?php _e('API Key', 'utrbr') ?></th>
            <td><input type="text" size="50" name="utrbr-apikey" value="<?php echo get_option('utrbr-apikey'); ?>" /></td>
          </tr>

      </table>
      </div>
  </div>
 
