 <?php
/**
 * Plugin Name: AgriLife Flexible Columns Addon: Cells
 * Plugin URI: https://github.com/AgriLife/agrilife-flexible-columns-cells
 * Description: WordPress plugin for adding functionality to the Flexible Columns page template included with the AgriFlex3 theme
 * Version: 1.0.0
 * Author: Zach Watkins
 * Author URI: http://github.com/ZachWatkins
 * Author Email: zachary.watkins@ag.tamu.edu
 * License: GPL2+
 */

define('AG_FCC_DIR_PATH', plugin_dir_path( __FILE__ ) );

// Add ACF fields to Flexible Columns field group
add_filter('acf/load_field/key=field_5772ab6603192', 'afcc_load_extras' );


/**
 * Adds fields to the Flexible Columns ACF field group
 * @param  array $field The target field group
 * @return array        The target field group
 */
function afcc_load_extras($field) {

	// Define ACF field keys
	$type_key = 'field_59107c430b640';
	$columns_title_key = 'field_59108cfb935ff';

  // Prevent this filter from running while editing exportable ACF field group data
  if(function_exists('get_current_screen')){
      $screen = get_current_screen();
      if(!is_null($screen) && $screen->post_type == 'acf-field-group')
          return $field;
  }

  // Add full content type to Columns row
  $cells = file_get_contents(AG_FCC_DIR_PATH . 'fields/fc-columns-cells.json');
  $cells_field = json_decode( $cells, true );
  $cells_subfield = $cells_field[0]['fields'];

  // Fields to add to the Columns row type
  $columntype = $cells_subfield[0];
  $textalignment = $cells_subfield[1];
  $columns = $cells_subfield[2];
  $widths = $cells_subfield[3];
  $content = $cells_subfield[4];

  // Merge "type" json field if one is already present
  foreach( $field['layouts'] as $value ){

    if($value['name'] == 'columns'){

    	foreach( $value['sub_fields'] as $subkey => $subvalue ){

		  	if( $subvalue['key'] == 'field_59107c430b640' ){

		  		// Field is in use and should be merged with existing value
		  		$newchoices = $columntype['choices'];
		  		unset($newchoices['summary']);
					$subvalue['choices'] += $newchoices;
					echo '<pre>';
					print_r($subvalue);
					echo '</pre>';
		  		return;

		  	}

		  }

	  }

  }
  // echo '<pre>';
  // print_r($field);
  // echo '</pre>';

  // Find the Columns field and insert the new fields
  foreach ($field['layouts'] as $key => $value) {

    if($value['name'] == 'columns'){

      // Add conditional logic to default columns fields
      foreach ($value['sub_fields'] as $subkey => $subvalue){

    		// The fields checked in the next line should always be present, so ones whose conditional logic will be modified should only be shown with the default Columns type.
        if( $subvalue['key'] != $columns_title_key ){

          // This field should not show if one of our new columns type values is active
          $conditions = array(
			  		array(
			  			"field" => $type_key,
			  			"operator" => "!=",
			  			"value" => "cells_summary"
			  		),
			  		array(
			  			"field" => $type_key,
			  			"operator" => "!=",
			  			"value" => "cells_full"
			  		)
			  	);

          if(is_array($field['layouts'][$key]['sub_fields'][$subkey]['conditional_logic'])){

          	$field['layouts'][$key]['sub_fields'][$subkey]['conditional_logic'][] = $conditions;

          } else {

	          $field['layouts'][$key]['sub_fields'][$subkey]['conditional_logic'] = array(
  						$conditions
					  );

          }

        }

      }

      // Insert the column type field after the first field
      array_splice(
        $field['layouts'][$key]['sub_fields'], 1, 0, array( $columntype )
      );

      // Insert the other new fields at the end
      $field['layouts'][$key]['sub_fields'][] = $textalignment;
      $field['layouts'][$key]['sub_fields'][] = $columns;
      $field['layouts'][$key]['sub_fields'][] = $widths;
      $field['layouts'][$key]['sub_fields'][] = $content;
      break;

    }

  }

  return $field;

}

// Remove ACF inline styles for WYSIWYG
function my_acf_input_admin_footer() { ?>
	<script type="text/javascript">
		(function($) {
			acf.add_action('wysiwyg_tinymce_init', function( ed, id, mceInit, $field ){
				$(".acf-field .acf-editor-wrap iframe").removeAttr("style");
			});
		})(jQuery);
	</script>
<?php }

add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');
