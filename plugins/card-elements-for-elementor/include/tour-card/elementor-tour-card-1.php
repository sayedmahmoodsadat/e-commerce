<!-- Start Tour Card 1 -->

<?php

use Elementor\Icons_Manager; ?>

<div class="tour-card-style-1">
    <div class="tour-main-container">
        <div class="tour-img-content">
            <img src="<?php echo esc_url($settings['place_image']['url']); ?>" class="tour-img" alt="<?php echo esc_html($settings['place_name']); ?>" />

            <div class="tour-img-overlay"></div>

        </div>
        <div class="tour-container">
            <div class="tour-content">
                <?php
                $icon1 = $icon2 = $icon3 = '';
                if (!empty($settings['tour_sale_icon']['value'])) {
                    ob_start();
                    Icons_Manager::render_icon($settings['tour_sale_icon'], ['aria-hidden' => 'true']);
                    $sale_icon = ob_get_clean();
                }
                if ($settings['tour_sale'] == "yes") { ?>
                    <div class="elementor-tour-sale-wrapper">
                        <span class="tour-sale-icon">
                            <?php echo $sale_icon; ?>
                        </span>
                        <span class="tour-sale-text">
                            <?php echo esc_html($settings['tour_sale_text']); ?>
                        </span>
                    </div>
                    <?php
                } else {
                    ?>
                    <div  class="elementor-tour-sale-wrapper" style="display: none!important; background: none!important;"></div>
                    <?php
                }
                ?>

                <div class="elementor-tour-name-wrapper"><?php echo esc_html($settings['place_name']); ?></div>
                <div class="elementor-tour-price-wrapper"><?php echo esc_html($settings['tour_price']); ?></div>
                <div class="elementor-tour-description-wrapper"><?php echo esc_html($settings['tour_description']); ?></div>
            </div>

            <div class="tour-details">
                <ul class="tour-detail-ul">

                    <?php
                    if ($settings['tour_days_icon']['value']) {
                        ob_start();
                        Icons_Manager::render_icon($settings['tour_days_icon'], ['aria-hidden' => 'true']);
                        $icon1 = ob_get_clean();
                    }
                    ?>

                    <li class="tour-detail-list"><p class="tour-detail-icon"><?php echo $icon1; ?></p><p class="tour-detail-text"><?php echo esc_html($settings['tour_days']); ?> <?php esc_html_e('Days', 'card-elements-for-elementor') ?></p></li>

                    <?php
                    if ($settings['tour_person_icon']['value']) {
                        ob_start();
                        Icons_Manager::render_icon($settings['tour_person_icon'], ['aria-hidden' => 'true']);
                        $icon2 = ob_get_clean();
                    }
                    ?>

                    <li class="tour-detail-list"><p class="tour-detail-icon"><?php echo $icon2; ?></p><p class="tour-detail-text"><?php echo esc_html($settings['tour_person']); ?> <?php esc_html_e('Persons', 'card-elements-for-elementor') ?></p></li>

                    <?php
                    if ($settings['tour_guide_icon']['value']) {
                        ob_start();
                        Icons_Manager::render_icon($settings['tour_guide_icon'], ['aria-hidden' => 'true']);
                        $icon3 = ob_get_clean();
                    }
                    ?>

                    <li class="tour-detail-list"><p class="tour-detail-icon"><?php echo $icon3; ?></p><p class="tour-detail-text"><?php echo esc_html($settings['tour_guides']); ?> <?php esc_html_e('Guides', 'card-elements-for-elementor') ?></p></li>
                </ul>
            </div>

            <div class="tour-button">
                <a href="<?php echo esc_url($tour_btn_link['url']); ?>" <?php echo esc_attr($target); echo esc_attr($rel); ?> class="view-tour-btn"><?php echo esc_html($settings['button_text']); ?></a>
            </div>
        </div>

        <?php
        if ($settings['display_whatsapp_share'] == 'yes') { ?>
            <div class="tour-card-share">
                <a href="<?php echo esc_url("https://api.whatsapp.com/send?text=".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" target="_blank"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
            </div>
            <?php
        } else {
            ?>
            <div class="tour-card-share" style="display: none;">
            </div>
            <?php
        }
        ?>
    </div>
</div>
<!-- End Tour Card -->
