jQuery(document).ready(function () {
    jQuery('.ct-store__container').hide();

    jQuery("input[name='radio-edit-type']").change(function() {
        const editDetailsType = jQuery("input[name='radio-edit-type']:checked").val();

        if (editDetailsType === 'radio-edit-user') {
            jQuery('.ct-user__container').fadeIn().siblings('.ct-store__container').hide();
        } else if (editDetailsType === 'radio-edit-store') {
            jQuery('.ct-store__container').fadeIn().siblings('.ct-user__container').hide();
        }
    });

    jQuery(".tp-tooltip-view").click(function() {
        var row = jQuery(this).closest("tr");
        var orderId = row.find('.data-order-id').html();
        var productName = row.find('.data-product-name').html();
        var productPrice = row.find('.data-product-price').html();
        var addressId = row.find('.data-address-id').html();
        var paymentMode = row.find('.data-terminal-payment-mode').html();
        var terminalRef = row.find('.data-terminal-ref').html();
        var createdAt = row.find('.data-created-at').html();

        var paymentId = jQuery(this).attr('data-payment-id');
        var tid = jQuery(this).attr('data-terminal-tid');
        var mid = jQuery(this).attr('data-terminal-mid');
        var appr = jQuery(this).attr('data-terminal-appr');
        var transactionId = jQuery(this).attr('data-transaction-id');

        jQuery('#modal-transaction-details').find('#data-order-id').html(orderId);
        jQuery('#modal-transaction-details').find('#data-product-name').html(productName);
        jQuery('#modal-transaction-details').find('#data-product-price').html(productPrice);
        jQuery('#modal-transaction-details').find('#data-address-id').html(addressId);
        jQuery('#modal-transaction-details').find('#data-payment-mode').html(paymentMode);
        jQuery('#modal-transaction-details').find('#data-payment-id').html(paymentId);
        jQuery('#modal-transaction-details').find('#data-terminal-ref').html(terminalRef);
        jQuery('#modal-transaction-details').find('#data-terminal-mid').html(mid);
        jQuery('#modal-transaction-details').find('#data-terminal-appr').html(appr);
        jQuery('#modal-transaction-details').find('#data-terminal-tid').html(tid);
        jQuery('#modal-transaction-details').find('#data-transaction-id').html(transactionId);
        jQuery('#modal-transaction-details').find('#data-created-at').html(createdAt);
    });

    tippy('.tp-tooltip-download', {
        content: 'Download Zip File',
        theme: 'custom',
        arrow: false,
    });
    tippy('.tp-tooltip-manage', {
        content: 'Manage',
        theme: 'custom',
        arrow: false,
    });
    tippy('.tp-tooltip-delete', {
        content: 'Delete',
        theme: 'custom',
        arrow: false,
    });
    tippy('.tp-tooltip-view', {
        content: 'View Details',
        theme: 'custom',
        arrow: false,
    });
    tippy('.tp-tooltip-edit', {
        content: 'Edit Details',
        theme: 'custom',
        arrow: false,
    });

});
