<?php

function adm_gallery_menu() {
	add_options_page( 'Admium Gallery', 'Admium Gallery', 'manage_options', 'adm-gallery', 'adm_gallery_options' );
}
add_action( 'admin_menu', 'adm_gallery_menu' );

function adm_gallery_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if( isset($_POST['option_page']) && $_POST['option_page'] == 'adm-gallery-settings-group' ) {
	    // save settings
	    update_option( 'adm_gallery_custom_thumbnail', $_POST['adm_gallery_custom_thumbnail'] );
	    update_option( 'adm_gallery_custom_thumbnail_width', $_POST['adm_gallery_custom_thumbnail_width'] );
	    update_option( 'adm_gallery_custom_thumbnail_height', $_POST['adm_gallery_custom_thumbnail_height'] );
	    update_option( 'adm_gallery_custom_thumbnail_crop', $_POST['adm_gallery_custom_thumbnail_crop'] );
	    update_option( 'adm_gallery_css', $_POST['adm_gallery_css'] );
	    update_option( 'adm_gallery_columns', $_POST['adm_gallery_columns'] );
	    update_option( 'adm_gallery_orderby', $_POST['adm_gallery_orderby'] );
	    update_option( 'adm_gallery_order', $_POST['adm_gallery_order'] );
	}
	
	?>
	<style>
    	#admium_gallery_header
    	{
    		border: solid 1px #c6c6c6;
    		margin: 12px 2px 8px 2px;
    		padding: 20px;
    		background-color: #e1e1e1;
    		float: left;
    	}
    	#admium_gallery_header h4
    	{
        	margin: 0px 0px 0px 0px;
    	}
    	#admium_gallery_header tr
    	{
        	vertical-align: top;
    	}
    </style>
    <script>
    
        jQuery( function( $ ) {
            $('#adm_gallery_custom_thumbnail_1').click( function() { 
           	     $('#adm_gallery_custom_thumbnail_settings').fadeIn("fast");
           	} );
           	
            $('#adm_gallery_custom_thumbnail_0').click( function() { 
           	     $('#adm_gallery_custom_thumbnail_settings').fadeOut("fast");
           	} );
        });
    
    </script>
    
    <div class="wrap">
    
	<?php

    echo '<h2>Admium Gallery '.__("settings",'adm-gallery').'</h2>';
    
    ?>
    <div id="admium_gallery_header">
    	<h4><?php _e("How to use this plugin?",'adm-gallery'); ?></h4>
		<p><?php _e("Copy and paste the shortcode <b>[adm_gallery]</b> into the editor of the page on which you want to show the gallery. The page will automatically display an overview of all child-pages which contain a gallery.", 'adm-gallery'); ?></p>
    </div>
    
    <br style="clear:both" />
    
    <form method="post" action="">
    <?php settings_fields( 'adm-gallery-settings-group' ); ?>

        <h2><?php _e("Custom thumbnails",'adm-gallery'); ?></h2>

		<table class="form-table">
    		<tr valign="middle">
        		<th scope="row"><?php _e("Enable custom thumbnails?",'adm-gallery'); ?></th>
        		<td>
        		    <?php $adm_gallery_custom_thumbnail = get_option('adm_gallery_custom_thumbnail',ADM_GALLERY_CUSTOM_THUMBNAIL); ?>
        		    <input type="radio" style="" name="adm_gallery_custom_thumbnail" id="adm_gallery_custom_thumbnail_1" value="1" <?php if ($adm_gallery_custom_thumbnail == 1){ echo "checked";}?>><label for="adm_gallery_custom_thumbnail_1"><?php _e("Yes",'adm-gallery'); ?></label><br/><input type="radio" style="" name="adm_gallery_custom_thumbnail" id="adm_gallery_custom_thumbnail_0" value="0" <?php if ($adm_gallery_custom_thumbnail == 0){ echo "checked";}?>><label for="adm_gallery_custom_thumbnail_0"><?php _e("No",'adm-gallery'); ?></label>
        		    <br/>
                    <i><?php _e("Tip: When set to yes a new set of thumbnails are created for this plugin, set to no to use the default Wordpress thumbnails. These new thumbnails are only created for images uploaded from now on, use a plugin like \"Force Regenerate Thumbnails\" to regenerate thumbnails.", 'adm-gallery'); ?></i>
        		</td>
    		</tr>		
		</table>
		
		<table class="form-table" id="adm_gallery_custom_thumbnail_settings" <?php if ($adm_gallery_custom_thumbnail == 0){ echo "style='display:none;'"; }?>>
    		<tr valign="middle">
        		<th scope="row"><?php _e("Custom thumbnail dimensions",'adm-gallery'); ?></th>
        		<td>
            		<?php _e("Crop and fit within",'adm-gallery'); ?> <input type="text" style="width: 50px;" name="adm_gallery_custom_thumbnail_width" value="<?php echo get_option('adm_gallery_custom_thumbnail_width',ADM_GALLERY_CUSTOM_THUMBNAIL_WIDTH); ?>" />
            		x <input type="text" style="width: 50px;" name="adm_gallery_custom_thumbnail_height" value="<?php echo get_option('adm_gallery_custom_thumbnail_height',ADM_GALLERY_CUSTOM_THUMBNAIL_HEIGHT); ?>" /> <?php _e("pixels",'adm-gallery'); ?>
            		<br/>
            		<i><?php _e("Tip: Aspect ratio of 3:2 is most common in photos, keep this in mind when entering dimensions!", 'adm-gallery'); ?></i>
        		</td>
    		</tr>
    		
    		<tr valign="middle">
        		<th scope="row"><?php _e("Hard crop thumbnails?",'adm-gallery'); ?></th>
        		<td>
        		    <?php $adm_gallery_custom_thumbnail_crop = get_option('adm_gallery_custom_thumbnail_crop',ADM_GALLERY_CUSTOM_THUMBNAIL_CROP); ?>
        		    <input type="radio" style="" name="adm_gallery_custom_thumbnail_crop" id="adm_gallery_custom_thumbnail_crop_1" value="1" <?php if ($adm_gallery_custom_thumbnail_crop == 1){ echo "checked";}?>><label for="adm_gallery_custom_thumbnail_crop_1"><?php _e("Yes",'adm-gallery'); ?></label><br/><input type="radio" style="" name="adm_gallery_custom_thumbnail_crop" id="adm_gallery_custom_thumbnail_crop_0" value="0" <?php if ($adm_gallery_custom_thumbnail_crop == 0){ echo "checked";}?>><label for="adm_gallery_custom_thumbnail_crop_0"><?php _e("No",'adm-gallery'); ?></label>
        		    <br/>
                    <i><?php _e("Tip: When set to yes all the thumbnails will be the exact size, when set to no the original aspect ratio is kept and the thumbnail will fit within above dimensions.", 'adm-gallery'); ?></i>
        		</td>
    		</tr>		
		</table>
		
		<hr/>
		
		<h2><?php _e("Style",'adm-gallery'); ?></h2>

		<table class="form-table">
    		<tr valign="middle">
    		<th scope="row"><?php _e("Use built-in stylesheet?",'adm-gallery'); ?></th>
    		<td>
    		    <?php $adm_gallery_css = get_option('adm_gallery_css',ADM_GALLERY_CSS); ?>
    		    <input type="radio" style="" name="adm_gallery_css" id="adm_gallery_css_1" value="1" <?php if ($adm_gallery_css == 1){ echo "checked";}?>><label for="adm_gallery_css_1"><?php _e("Yes",'adm-gallery'); ?></label><br/><input type="radio" style="" name="adm_gallery_css" id="adm_gallery_css_0" value="0" <?php if ($adm_gallery_css == 0){ echo "checked";}?>><label for="adm_gallery_css_0"><?php _e("No",'adm-gallery'); ?></label>
    		    <br/>
                <i><?php _e("Tip: Set to yes to enable built-in CSS, set to no if you want to style everything yourself.", 'adm-gallery'); ?></i>
    		</td>
    		</tr>
    		
    		<tr valign="middle">
    		<th scope="row"><?php _e("Number of columns",'adm-gallery'); ?></th>
    		<td>
    		    <?php $adm_gallery_columns = get_option('adm_gallery_columns',ADM_GALLERY_COLUMNS); ?>
    		    <select size="1" name="adm_gallery_columns">
    		    <?php
    		        for ($i = 1; $i<10; $i++){
        		        ?>
        		        <option value="<?php echo $i; ?>" <?php if ($adm_gallery_columns == $i){ echo "selected";}?>><?php echo $i; ?></option>
        		        <?php
        		        
        		        
    		        }
    		    ?>
    		    </select>
    		    <br/>
                <i><?php _e("Tip: Depends on the used theme what value is best, just play with it.", 'adm-gallery'); ?></i>
    		</td>
    		</tr>
		</table>
		
		<hr/>
		
		<h2><?php _e("Order",'adm-gallery'); ?></h2>

		<table class="form-table">
    		<tr valign="middle">
    		<th scope="row"><?php _e("Order gallery by",'adm-gallery'); ?></th>
    		<td>
    		    <?php $adm_gallery_orderby = get_option('adm_gallery_orderby',ADM_GALLERY_ORDERBY); ?>
    		    <select size="1" name="adm_gallery_orderby">
                <option value="modified" <?php if ($adm_gallery_orderby == "modified"){ echo "selected";}?>><?php _e("Date modified", 'adm-gallery'); ?></option>
                <option value="date" <?php if ($adm_gallery_orderby == "date"){ echo "selected";}?>><?php _e("Date created", 'adm-gallery'); ?></option>
                <option value="title" <?php if ($adm_gallery_orderby == "title"){ echo "selected";}?>><?php _e("Title", 'adm-gallery'); ?></option>
                <option value="comment_count" <?php if ($adm_gallery_orderby == "comment_count"){ echo "selected";}?>><?php _e("Comment count", 'adm-gallery'); ?></option>
                <option value="rand" <?php if ($adm_gallery_orderby == "rand"){ echo "selected";}?>><?php _e("Random", 'adm-gallery'); ?></option>
    		    </select>
    		</td>
    		</tr>
    		
    		<tr valign="middle">
    		<th scope="row"><?php _e("Order",'adm-gallery'); ?></th>
    		<td>
    		    <?php $adm_gallery_order = get_option('adm_gallery_order',ADM_GALLERY_ORDER); ?>
    		    <input type="radio" style="" name="adm_gallery_order" id="adm_gallery_order_asc" value="ASC" <?php if ($adm_gallery_order == "ASC"){ echo "checked";}?>><label for="adm_gallery_order_asc"><?php _e("Ascending",'adm-gallery'); ?></label><br/><input type="radio" style="" name="adm_gallery_order" id="adm_gallery_order_desc" value="DESC" <?php if ($adm_gallery_order == "DESC"){ echo "checked";}?>><label for="adm_gallery_order_desc"><?php _e("Descending",'adm-gallery'); ?></label>
    		</td>
    		</tr>
		</table>
		
		<hr/>

		<input type="submit" class="button button-primary" value="<?php _e("Save changes", 'adm-gallery'); ?>" />

    </form>
    
    </div>
    
<?php
}
