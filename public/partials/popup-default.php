<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/* Template Name: Popup Default */

$wdpops = WDPOP_Helper::get_all_active_popups();

foreach ($wdpops as $item_id => $this_popup) {

  if(!WDPOP_Helper::should_show_popup($this_popup)) continue; // Hide popup if it is hidden by display options
  
  $popup_name = $this_popup['popup_name'];
  
  $item_tab = $this_popup['wdpop_item_tab'];
  $wdpop_image = $item_tab['popup_image'];
  $wdpop_image_link = $item_tab['popup_image_link']['url'];
  $wdpop_image_link_target = $item_tab['popup_image_link']['target'];
  $popup_type = $item_tab['popup_type'];
  
  $width_class = $item_tab['popup_width'] ? " {$item_tab['popup_width']}" : ''; //Possible values: .modal-fullscreen,  modal-xl, modal-lg, modal-md, modal-sm
  
  $fullscreen_class = $item_tab['fullscreen_popup'] ?  " {$item_tab['fullscreen_popup']}" : '';
  
  $style_custom_width = '';
  if($width_class == ' modal-custom-width'){
    $style_custom_width = $item_tab['popup_custom_width'] ? " style='max-width: {$item_tab['popup_custom_width']['width']}{$item_tab['popup_custom_width']['unit']}'" : '';
  }

  $position = $item_tab['popup_position'] ? " {$item_tab['popup_position']}" : '';
  if(strpos($position, "middle") || strpos($position, "bottom")) $position .= ' modal-dialog-centered';

  ?>


  <!-- Wing Popup -->
  <div class="modal fade wing-modal--item wing-modal--<?php echo esc_attr($item_id);?>" id="wingModal-<?php echo esc_attr($item_id);?>" wing-popup-id="<?php echo esc_attr($item_id);?>" tabindex="-1" aria-labelledby="wingModal-<?php echo esc_attr($item_id);?>Label" aria-hidden="true">
    <div class="modal-dialog<?php echo esc_attr($width_class.$fullscreen_class.$position); ?>" <?php echo wp_kses_post($style_custom_width); ?>>
      <div class="modal-content wing--popup-contentX wdpop_core--popup-area">
        <span type="buttonX" class="btn-closeX wing--popup-close-btn" data-bs-dismiss="modal" aria-label="Close"><?php echo esc_html('&times;');?></span>
        <div class="wing--popup-wrapper">
          <?php if($wdpop_image_link AND $wdpop_image){ ?>
              <a href="<?php echo esc_url($wdpop_image_link); ?>" target="<?php echo esc_attr($wdpop_image_link_target);?>">
          <?php } ?>
              <?php if($wdpop_image) { ?>
                <div class="wing--popup-image">
                    <img src="<?php echo esc_url($wdpop_image);?>" alt="<?php echo esc_attr($popup_name);?>" loading="lazy" class="wing-popup--image">
                </div>
              <?php } ?>
          <?php if($wdpop_image_link AND $wdpop_image){ ?>
            </a>
          <?php } ?>
              <?php if($popup_type == 'image_and_content') { ?>
                <div class="wing--popup-content-wrap text-center">
                  <?php if($item_tab['popup_title']) { ?>
                      <h1 class="fs-5 wing--popup-title"><?php echo wp_kses_post($item_tab['popup_title']);?></h1>
                  <?php } ?>
                  <?php if($item_tab['Popup_content']) { ?>
                    <div class="wing--popup-content">
                      <?php echo wp_kses_post($item_tab['Popup_content']);?>
                    </div>
                  <?php } ?>

                  <?php if($item_tab['popup_close_text']) { ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo esc_html($item_tab['popup_close_text']);?></button>
                  <?php } ?>
                  
                  <?php if($item_tab['cta_link']['text']) { ?>
                    <a type="button" class="btn btn-primary" href="<?php echo esc_url($item_tab['cta_link']['url']); ?>" target="<?php echo esc_attr($item_tab['cta_link']['target']);?>"><?php echo esc_html($item_tab['cta_link']['text']);?></a>
                  <?php } ?>
                </div>
              <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>


