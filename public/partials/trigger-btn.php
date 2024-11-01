<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  ?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".wing-modal--<?php echo esc_attr($item_id);?>">
    <?php echo esc_html('Launch demo modal '.$item_id);?>
</button>