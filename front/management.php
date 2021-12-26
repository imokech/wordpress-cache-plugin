<?php ?>


<div>

<form method="post">
    <button name="flush_all_cache" type="submit"><?php echo __('Flush All Cache', Text_Domain); ?></button>
    <input type="hidden" id="_wpnonce_purge" name="_wpnonce_purge" value="<?php echo wp_create_nonce();?>" />
</form>

</div>