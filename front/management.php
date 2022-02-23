<div class="ci-management-panel" dir="ltr">
    <div class="cache">
        <div class="cache-details">
            <ul>
                <li>Count of Cached Files: <span><?php echo esc_html($cacheCount); ?></span></li>
                <li>Size on desk: <span><?php echo esc_html($cacheSize); ?></span></li>
            </ul>
        </div>
        <form method="post">
            <button name="flush_all_cache" type="submit"><?php echo __('Flush All Cache', 'cinnamon-cache'); ?></button>
            <input type="hidden" id="_wpnonce_purge" name="_wpnonce_purge" value="<?php echo wp_create_nonce('_wpnonce_purge');?>" />
        </form>
        <form method="post" action="">
            <input type="text" name="cache_time" value="<?php echo get_option('_ci_cache_time') ? get_option('_ci_cache_time') : ''; ?>">
            <button name="set_cache_time" type="submit"><?php echo __('Set Time', 'cinnamon-cache'); ?></button>
            <input type="hidden" id="_wpnonce_setting" name="_wpnonce_setting" value="<?php echo wp_create_nonce('_wpnonce_setting');?>" />
        </form>
    </div>
</div>