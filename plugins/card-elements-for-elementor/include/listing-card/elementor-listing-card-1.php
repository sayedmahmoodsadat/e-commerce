<!-- Start Listing Card 1 -->
<div class="listing-card-style-1">
    <div class="listing-main-container">
        <div class="listing-img-content">
            <img src="<?php echo esc_url($settings['select_image']['url']); ?>" class="listing-img" alt="<?php echo esc_html($settings['name']); ?>" />
            <div class="listing-img-overlay"></div>
        </div>
        <div class="listing-content">
            <div class="elementor-listing-name-wrapper"><?php echo esc_html($settings['name']); ?></div>
            <div class="elementor-listing-description-wrapper"><?php echo esc_html($settings['listing_description']); ?></div>
            <div class="elementor-listing-price-wrapper"><?php echo esc_html($settings['listing_price']); ?></div>
            <div class="elementor-star-rating__wrapper">
                <?php echo wp_kses_post($stars_element); ?>
            </div>
        </div>
        <div class="listing-button">
            <a href="<?php echo esc_url($listing_btn_link['url']); ?>" <?php
            echo esc_attr($target);
            echo esc_attr($rel);
            ?> class="view-listing-btn"><?php echo esc_html($settings['button_text']); ?></a>
        </div>

        <?php
        if ($settings['listing_whatsapp_share'] == 'yes') { ?>
            <div class="listing-card-share">
                <a href="<?php echo esc_url("https://api.whatsapp.com/send?text=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" target="_blank"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
            </div>
            <?php
        } else {
            ?> 
            <div class="listing-card-share" style="display: none;">
            </div>                      
        <?php } ?>
    </div>
</div>
<!-- End Listing Card -->
