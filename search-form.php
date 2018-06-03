<h1>Article Factory Search</h1>

<form method="POST">
    <label for="wpquery">Search for a topic:</label>
    <input type="text" name="wpquery" id="wpquery" value="<?php echo $value; ?>" required>
    <?php wp_nonce_field('wpshout_option_page_example_action'); ?>
    <input type="submit" value="Save" class="button button-primary button-large">
</form>
