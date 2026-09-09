<?php
/**
 * Event card used by the archive, taxonomy views, and shortcode.
 *
 * @var int   $post_id
 * @var array $se_event
 * @var array $se_args
 */

if (!defined('ABSPATH')) {
    exit;
}

$se_event          = isset($se_event) ? $se_event : Simple_Events_Helpers::get_event($post_id);
$se_args           = isset($se_args) ? $se_args : array();
$se_is_past        = !empty($se_args['is_past']);
$se_show_register  = !isset($se_args['show_register']) || $se_args['show_register'];
$se_date_display   = Simple_Events_Helpers::format_date_range($se_event['date']);
$se_time_display   = Simple_Events_Helpers::format_time_range($se_event['time']);
$se_location       = Simple_Events_Helpers::format_location($se_event);
$se_card_classes   = 'se-card';

if ($se_is_past) {
    $se_card_classes .= ' se-card--past';
}
?>
<article class="<?php echo esc_attr($se_card_classes); ?>">
    <a class="se-card__link" href="<?php echo esc_url(get_permalink($post_id)); ?>">
        <div class="se-card__image">
            <?php if ($se_is_past) : ?>
                <span class="se-badge se-badge--past"><?php esc_html_e('Past', 'simple-events-cpt'); ?></span>
            <?php elseif ($se_event['is_free']) : ?>
                <span class="se-badge se-badge--free"><?php esc_html_e('Free', 'simple-events-cpt'); ?></span>
            <?php endif; ?>

            <?php if (has_post_thumbnail($post_id)) : ?>
                <?php echo get_the_post_thumbnail($post_id, 'medium', array('alt' => esc_attr(get_the_title($post_id)))); ?>
            <?php else : ?>
                <div class="se-card__placeholder" aria-hidden="true"></div>
            <?php endif; ?>
        </div>

        <div class="se-card__body">
            <h3 class="se-card__title"><?php echo esc_html(get_the_title($post_id)); ?></h3>
            <div class="se-card__meta">
                <?php if ($se_date_display) : ?>
                    <span class="se-card__date"><?php echo esc_html($se_date_display); ?></span>
                <?php endif; ?>
                <?php if ($se_time_display) : ?>
                    <span class="se-card__time"><?php echo esc_html($se_time_display); ?></span>
                <?php endif; ?>
            </div>
            <?php if ($se_location) : ?>
                <p class="se-card__location"><?php echo esc_html($se_location); ?></p>
            <?php endif; ?>
            <?php if ($se_event['short_description']) : ?>
                <p class="se-card__excerpt"><?php echo esc_html(wp_trim_words($se_event['short_description'], 20)); ?></p>
            <?php endif; ?>
        </div>
    </a>

    <div class="se-card__actions">
        <a class="se-button se-button--secondary" href="<?php echo esc_url(get_permalink($post_id)); ?>">
            <?php echo $se_is_past ? esc_html__('View Details', 'simple-events-cpt') : esc_html__('Learn More', 'simple-events-cpt'); ?>
        </a>
        <?php if ($se_show_register && !$se_is_past && !empty($se_event['registration_link'])) : ?>
            <a class="se-button se-button--primary" href="<?php echo esc_url($se_event['registration_link']); ?>" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e('Register', 'simple-events-cpt'); ?>
            </a>
        <?php endif; ?>
    </div>
</article>
