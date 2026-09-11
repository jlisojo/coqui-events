<?php
/**
 * Single event template.
 *
 * Copy this file to your theme as `single-se_event.php` or
 * `simple-events/single-se_event.php` to override it.
 */

if (!defined('ABSPATH')) {
    exit;
}

$post_id = get_the_ID();
$se_event   = Simple_Events_Helpers::get_event($post_id);

$se_date_display = Simple_Events_Helpers::format_date_range($se_event['date'], $se_event['end_date']);
$se_time_display = Simple_Events_Helpers::format_time_range($se_event['time'], $se_event['end_time']);
$se_cta_label    = $se_event['is_free'] ? __('Register Now', 'coqui-events') : __('Register / Buy Tickets', 'coqui-events');
$se_map_query    = trim($se_event['address'] . ' ' . $se_event['city'] . ' ' . $se_event['state'] . ' ' . $se_event['zip']);

get_header();
?>

<main class="se-single" itemscope itemtype="https://schema.org/Event">
    <div class="se-single__inner">
        <?php while (have_posts()) : the_post(); ?>
            <article class="se-single__article">
                <header class="se-single__header">
                    <h1 class="se-single__title" itemprop="name"><?php the_title(); ?></h1>
                    <?php if ($se_event['registration_link']) : ?>
                        <a class="se-button se-button--primary se-single__cta-mobile" href="<?php echo esc_url($se_event['registration_link']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html($se_cta_label); ?>
                        </a>
                    <?php endif; ?>
                </header>

                <div class="se-single__layout">
                    <div class="se-single__main">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="se-single__image">
                                <?php if ($se_event['is_free']) : ?>
                                    <span class="se-badge se-badge--free"><?php esc_html_e('Free Event', 'coqui-events'); ?></span>
                                <?php elseif ($se_event['price']) : ?>
                                    <?php /* translators: %s: event price, including currency symbol. */ ?>
                                    <span class="se-badge se-badge--price"><?php echo esc_html(sprintf(__('From %s', 'coqui-events'), Simple_Events_Helpers::format_price($se_event['price']))); ?></span>
                                <?php endif; ?>
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="se-single__content" itemprop="description">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <aside class="se-single__sidebar">
                        <?php if ($se_event['registration_link']) : ?>
                            <div class="se-panel se-panel--cta">
                                <a class="se-button se-button--primary se-button--block" href="<?php echo esc_url($se_event['registration_link']); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo esc_html($se_cta_label); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="se-panel">
                            <h2><?php esc_html_e('Event Details', 'coqui-events'); ?></h2>
                            <?php if ($se_date_display) : ?>
                                <div class="se-panel__row">
                                    <strong><?php esc_html_e('Date', 'coqui-events'); ?></strong>
                                    <span><?php echo esc_html($se_date_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($se_time_display) : ?>
                                <div class="se-panel__row">
                                    <strong><?php esc_html_e('Time', 'coqui-events'); ?></strong>
                                    <span><?php echo esc_html($se_time_display); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($se_event['age_range']) : ?>
                                <div class="se-panel__row">
                                    <strong><?php esc_html_e('Ages', 'coqui-events'); ?></strong>
                                    <span><?php echo esc_html($se_event['age_range']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($se_event['capacity']) : ?>
                                <div class="se-panel__row">
                                    <strong><?php esc_html_e('Capacity', 'coqui-events'); ?></strong>
                                    <span><?php echo esc_html($se_event['capacity']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($se_event['location']) : ?>
                            <div class="se-panel">
                                <h2><?php esc_html_e('Location', 'coqui-events'); ?></h2>
                                <p class="se-panel__venue"><strong><?php echo esc_html($se_event['location']); ?></strong></p>
                                <?php if ($se_event['address']) : ?>
                                    <address>
                                        <?php echo esc_html($se_event['address']); ?><br>
                                        <?php if ($se_event['city'] || $se_event['state'] || $se_event['zip']) : ?>
                                            <?php echo esc_html(trim($se_event['city'] . ', ' . $se_event['state'] . ' ' . $se_event['zip'], ', ')); ?>
                                        <?php endif; ?>
                                    </address>
                                <?php endif; ?>
                                <?php if ($se_map_query) : ?>
                                    <a class="se-map-link" href="<?php echo esc_url('https://maps.google.com/?q=' . rawurlencode($se_map_query)); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php esc_html_e('View on Map', 'coqui-events'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!$se_event['is_free'] && ($se_event['price'] || $se_event['price_child'] || $se_event['price_adult'])) : ?>
                            <div class="se-panel">
                                <h2><?php esc_html_e('Pricing', 'coqui-events'); ?></h2>
                                <?php if ($se_event['price']) : ?>
                                    <div class="se-panel__row">
                                        <strong><?php esc_html_e('General', 'coqui-events'); ?></strong>
                                        <span><?php echo esc_html(Simple_Events_Helpers::format_price($se_event['price'])); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($se_event['price_child']) : ?>
                                    <div class="se-panel__row">
                                        <strong><?php esc_html_e('Child', 'coqui-events'); ?></strong>
                                        <span><?php echo esc_html(Simple_Events_Helpers::format_price($se_event['price_child'])); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($se_event['price_adult']) : ?>
                                    <div class="se-panel__row">
                                        <strong><?php esc_html_e('Adult', 'coqui-events'); ?></strong>
                                        <span><?php echo esc_html(Simple_Events_Helpers::format_price($se_event['price_adult'])); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($se_event['phone'] || $se_event['email'] || $se_event['website']) : ?>
                            <div class="se-panel">
                                <h2><?php esc_html_e('Contact', 'coqui-events'); ?></h2>
                                <?php if ($se_event['phone']) : ?>
                                    <div class="se-panel__row">
                                        <strong><?php esc_html_e('Phone', 'coqui-events'); ?></strong>
                                        <a href="<?php echo esc_url('tel:' . $se_event['phone']); ?>"><?php echo esc_html($se_event['phone']); ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($se_event['email']) : ?>
                                    <div class="se-panel__row">
                                        <strong><?php esc_html_e('Email', 'coqui-events'); ?></strong>
                                        <a href="<?php echo esc_url('mailto:' . $se_event['email']); ?>"><?php echo esc_html($se_event['email']); ?></a>
                                    </div>
                                <?php endif; ?>
                                <?php if ($se_event['website']) : ?>
                                    <div class="se-panel__row">
                                        <strong><?php esc_html_e('Website', 'coqui-events'); ?></strong>
                                        <a href="<?php echo esc_url($se_event['website']); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Visit website', 'coqui-events'); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </aside>
                </div>

                <p class="se-single__back">
                    <a href="<?php echo esc_url(Simple_Events_Helpers::archive_url()); ?>">
                        <?php esc_html_e('← Back to all events', 'coqui-events'); ?>
                    </a>
                </p>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();
