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
    tippy('.tp-tooltip-delete', {
        content: 'Delete',
        theme: 'custom',
        arrow: false,
    });


    jQuery(".tp-tooltip-edit").click(function() {
        console.log("sdsd");
        var id = jQuery(this).attr('data-id');
        var machineId = jQuery(this).attr('data-machine-id');
        var name = jQuery(this).attr('data-machine-name');
        var desc = jQuery(this).attr('data-machine-desc');

        var modal_edit_machine = jQuery('#modal-edit-machine');

        modal_edit_machine.find('#id').val(id);
        modal_edit_machine.find('#machine_address_id').val(machineId);
        modal_edit_machine.find('#name').val(name);
        modal_edit_machine.find('#desc').val(desc);
    });

    jQuery(".tp-tooltip-delete").click(function() {
        var id = jQuery(this).attr('data-id');
        var delete_confirmation = jQuery('#modal-delete-confirmation');

        delete_confirmation.find('#id').val(id);
    });

});
