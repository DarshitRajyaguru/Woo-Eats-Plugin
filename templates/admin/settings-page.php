<div class="wrap">
    <h1>WooEats Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('wooeats_settings');
        do_settings_sections('wooeats');
        submit_button();
        ?>
    </form>
</div>
