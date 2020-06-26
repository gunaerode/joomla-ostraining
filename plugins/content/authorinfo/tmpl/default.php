<?php
/**
 * Author Info Plugin layout
 */

defined('_JEXEC') or die;

?>

<p><?php echo $author->name; ?></p>
<p><?php echo $author->email; ?></p>
<p><?php echo $profile->profile['address1']; ?></p>
<p><?php echo $profile->profile['city'] . ', ' . $profile->profile['region'] . ' ' . $profile->profile['postal_code']; ?></p>
<?php if($custom_text): ?>
<div class="custom-text">
    <?php echo $custom_text; ?>
</div>
<?php endif; ?>