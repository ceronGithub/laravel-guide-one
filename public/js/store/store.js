jQuery(document).ready(function () {
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

});

jQuery(".tp-tooltip-edit").click(function() {
    var id = jQuery(this).attr('data-store-id');
    var name = jQuery(this).attr('data-store-name');
    var desc = jQuery(this).attr('data-store-desc');

    console.log(name);

    var modal_edit_store = jQuery('#modal-edit-store');

    modal_edit_store.find('#store-id').val(id);
    modal_edit_store.find('#name').val(name);
    modal_edit_store.find('#desc').val(desc);
});

jQuery(".tp-tooltip-delete").click(function() {
    var id = jQuery(this).attr('data-id');
    var delete_confirmation = jQuery('#modal-delete-confirmation');

    delete_confirmation.find('#id').val(id);
});
