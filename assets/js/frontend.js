jQuery(document).ready(function($) {
    $('input[name^="wooeats_addons"]').change(function() {
        var totalAddonsPrice = 0;
        $('input[name^="wooeats_addons"]:checked').each(function() {
            totalAddonsPrice += parseFloat($(this).data('price'));
        });
        var originalPrice = parseFloat($('.woocommerce-Price-amount').data('price'));
        var newPrice = originalPrice + totalAddonsPrice;
        $('.woocommerce-Price-amount').text(newPrice.toFixed(2));
    });
});
