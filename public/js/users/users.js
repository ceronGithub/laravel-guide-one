jQuery(document).ready(function () {

    //-------------------------------------------------Start View-------------------------------
    jQuery(".tp-tooltip-view").click(function() {
        var row = jQuery(this).closest("tr");
        var email = row.find('.data-email').html();
        var roleName = row.find('.data-role-name').html();
        var activeName = row.find('.data-active-name').html();

        var firstName = jQuery(this).attr('data-first-name');
        var lastName = jQuery(this).attr('data-last-name');

        jQuery('#modal-product-details').find('#data-first-name').html(firstName);
        jQuery('#modal-product-details').find('#data-last-name').html(lastName);
        jQuery('#modal-product-details').find('#data-email').html(email);
        jQuery('#modal-product-details').find('#data-role-name').html(roleName);
        jQuery('#modal-product-details').find('#data-active-name').html(activeName);
    });
    //-------------------------------------------------End View-------------------------------

    //--------------------------(user most be super admin)Start Edit Button selected superAdmin account type----------------------------
    jQuery(".tp-tooltip-edit-superAdmin").click(function() {
        //---------------------------Start: Edit Button Clicked/Get Data--------------------------------------------------
        jQuery('#linkedAndUnlinkedSuperAdminAccount').empty();
        var row = jQuery(this).closest("tr");
        var email = row.find('.data-email').html();
        var roleName = row.find('.data-role-name').html();
        var activeName = row.find('.data-active-name').html();

        var firstName = jQuery(this).attr('data-first-name');
        var lastName = jQuery(this).attr('data-last-name');
        var roleId = jQuery(this).attr('data-role-id');
        var activeId = jQuery(this).attr('data-active-id');
        var Id = jQuery(this).attr('data-pass-id');
        //---------------------------End: Edit Button Clicked/Get Data--------------------------------------------------
        //---------------------------Start: change tabs according to selected role--------------------------------------
        var div1 = document.getElementById('roleAdminDisplayStore');
        var div2 = document.getElementById('roleSuperAdminDisplayVending');
        //---------------------------End: change tabs according to selected role--------------------------------------
        //---------------------------Start: 2nd tab display, according to selected role--------------------------------------
        var tabDiv1 = document.getElementById('storeTabSuperAdmin');
        var tabDiv2 = document.getElementById('vendingTabSuperAdmin');
        //---------------------------End: 2nd tab display, according to selected role--------------------------------------


        //display store list or vendingmachine list
        var displayVending = document.getElementById('roleSuperAdminDisplayVending');
        var displayStore = document.getElementById('changeRoleBackToAdminFromSuperAdmin');

        //hidden this div. when edit button is clicked.
        displayVending.style.visibility = 'hidden';
        displayStore.style.visibility = 'hidden';
        //check what user role has been selected
        if(roleId == 1)
        {
            div2.style.visibility = 'hidden';
            tabDiv2.style.visibility = 'hidden';
            div1.style.visibility = 'hidden';
            tabDiv1.style.visibility = 'hidden';
        }
        else if(roleId > 1)
        {
            div1.style.visibility = 'visible';
            tabDiv1.style.visibility = 'visible';
            div2.style.visibility = 'hidden';
            tabDiv2.style.visibility = 'hidden';
        }
        //----------------------------------------Start: pass data to modal-edit-user-superAdmin-----------------------------------
        jQuery('#modal-edit-user-superAdmin').find('#superAdmin-data-first-name').val(firstName);
        jQuery('#modal-edit-user-superAdmin').find('#superAdmin-data-last-name').val(lastName);
        jQuery('#modal-edit-user-superAdmin').find('#superAdmin-data-email').val(email);
        jQuery('#modal-edit-user-superAdmin').find('#superAdmin-data-role-id').val(roleId);
        jQuery('#modal-edit-user-superAdmin').find('#superAdmin-data-active-id').val(activeId);
        jQuery('#modal-edit-user-superAdmin').find('#superAdmin-ID').val(Id);
        //----------------------------------------End: pass data to modal-edit-user-superAdmin-----------------------------------
        jQuery('#modal-edit-user-superAdmin').find('#getUserId').val(Id);
        /*
            note:
                when this modal is open(tp-tooltip-edit-superAdmin). ajax run
                when modal is hidden or closed, page refresh.
        */
        jQuery.ajax({
            type: "GET",
            data: { getID: Id },
            success: function(response)
            {
                var unlinkData = jQuery.parseJSON(response.unlinkStores);
                var linkedData = jQuery.parseJSON(response.linkedStores);
                var join_data = [...unlinkData,...linkedData];

                unlinkData.forEach((item1, index1) => {
                    var found = false;
                    linkedData.forEach((item2, index2) => {
                        if(item1.id == item2.id)
                        {
                            found = true;
                        }
                    });
                    jQuery("#linkedAndUnlinkedSuperAdminAccount").append( found
                    ?
                    `
                    <div class="form-group ct-store__list">
                            <label for="LinkedStore${index1}" class="ct-list__container">${item1.name}
                            <input type="checkbox" id="LinkedStore${index1}"
                            name="LinkedStore[]" value="${item1.id}" checked>
                            <span class="checkmark"></span>
                        </label>
                        <input type="text" name="removeStore[]" value="0" id="UnlinkStore__${item1.id}" hidden>
                    </div>
                    `
                    :
                    `
                    <div class="form-group ct-store__list">
                        <label for="forLinkToUser${index1}" class="ct-list__container">${item1.name}
                            <input type="checkbox" id="forLinkToUser${index1}"
                                name="forLinkToUser[]" value="${item1.id}">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    `);
                    console.log(found);
                });

                const resultOfUnlinked = [];
                unlinkData.forEach((item) => {
                    resultOfUnlinked.push(item.id);
                });

                const resultOfLinked = [];
                linkedData.forEach((item) => {
                    resultOfLinked.push(item.id);
                });

                for (let index1 = 0; index1 <= unlinkData.length-1; index1++) {
                    //console.log(resultOfUnlinkData[index1]);
                    console.log("!Linked: "+index1);
                    for (let index2 = 0; index2 <= linkedData.length; index2++) {
                        if(resultOfUnlinked[index1] == resultOfLinked[index2])
                        {
                            console.log("Linked: "+index1+":"+index2);
                            break;
                        }
                    }
                }

                console.log(unlinkData);
                console.log(linkedData);
                console.log(resultOfUnlinked);
                console.log(resultOfLinked);
                console.log(join_data);
            }
        });
    });
    /* checkbox trigger, change value */
    jQuery(document).on('change', 'input[name="LinkedStore[]"]', function(){
        const store_id = jQuery(this).attr('value');
        console.log(jQuery(this).parent().parent());
        if(this.checked)
        {
            jQuery(this).parent().parent().find('input[name="removeStore[]"]').val(0);
        }
        else{
            jQuery(this).parent().parent().find('input[name="removeStore[]"]').val(store_id);
        }
    });
    //--------------------------(user most be super admin)End Edit Button selected superAdmin account type--------------------------

    //--------------------------(user most be admin)Start Edit Button----------------------------
    jQuery(".tp-tooltip-edit-admin").click(function() {
        //---------------------------Start: Edit Button Clicked/Get Data--------------------------------------------------
        var row = jQuery(this).closest("tr");
        var email = row.find('.data-email').html();
        var roleName = row.find('.data-role-name').html();
        var activeName = row.find('.data-active-name').html();

        var firstName = jQuery(this).attr('data-first-name');
        var lastName = jQuery(this).attr('data-last-name');
        var roleId = jQuery(this).attr('data-role-id');
        var activeId = jQuery(this).attr('data-active-id');
        var Id = jQuery(this).attr('data-pass-id');
        //---------------------------End: Edit Button Clicked/Get Data--------------------------------------------------
        jQuery('#modal-edit-user-admin').find('#admin-data-first-name').val(firstName);
        jQuery('#modal-edit-user-admin').find('#admin-data-last-name').val(lastName);
        jQuery('#modal-edit-user-admin').find('#admin-data-email').val(email);
        jQuery('#modal-edit-user-admin').find('#admin-data-role-id').val(roleId);
        jQuery('#modal-edit-user-admin').find('#admin-data-active-id').val(activeId);
        jQuery('#modal-edit-user-admin').find('#admin-ID').val(Id);

        //checked checkbox
        var  checkboxes = jQuery("input[type=checkbox][name='unlinkStore[]']");
        checkboxes.change(function() {
            if (this.checked) {
                document.getElementById('UnlinkStore__'+this.value).value = 0;
            } else {
                document.getElementById('UnlinkStore__'+this.value).value = this.value;
            }
        });
    });
    //--------------------------(user most be admin)End Edit Button----------------------------

    //--------------------------------------------Start Of update 2nd Tab---------------------------------------------
    //super admin tab js
    jQuery('.ct-vending__container').hide();
    jQuery("input[name='radio-edit-type']").change(function() {
        const editDetailsType = jQuery("input[name='radio-edit-type']:checked").val();

        if (editDetailsType === 'radio-edit-user') {
            jQuery('.ct-user__container').fadeIn().siblings('.ct-vending__container').hide();
        } else if (editDetailsType === 'radio-edit-vending') {
            jQuery('.ct-vending__container').fadeIn().siblings('.ct-user__container').hide();
        }
    });

    jQuery('.ct-store__container').hide();
    jQuery("input[name='radio-edit-type']").change(function() {
        const editDetailsType = jQuery("input[name='radio-edit-type']:checked").val();

        if (editDetailsType === 'radio-edit-user') {
            jQuery('.ct-user__container').fadeIn().siblings('.ct-store__container').hide();
        } else if (editDetailsType === 'radio-edit-store') {
            jQuery('.ct-store__container').fadeIn().siblings('.ct-user__container').hide();
        }
    });
    //--------------------------------------------End Of update 2nd Tab---------------------------------------------

    //--------------------------------------------Start Of Add User 2nd Tab---------------------------------------------
    jQuery('.ct-store').hide();
    jQuery("input[name='radio-create-type']").change(function() {
        const createDetailsType = jQuery("input[name='radio-create-type']:checked").val();

        if (createDetailsType === 'radio-create-user') {
            jQuery('.ct-addUser').fadeIn().siblings('.ct-store').hide();
        } else if (createDetailsType === 'radio-create-store') {
            jQuery('.ct-store').fadeIn().siblings('.ct-addUser').hide();
        }
    });

    jQuery('.ct-vendingmachine').hide();
    jQuery("input[name='radio-create-type']").change(function() {
        const createDetailsType = jQuery("input[name='radio-create-type']:checked").val();

        if (createDetailsType === 'radio-create-user') {
            jQuery('.ct-addUser').fadeIn().siblings('.ct-vendingmachine').hide();
        } else if (createDetailsType === 'radio-create-vendingmachine') {
            jQuery('.ct-vendingmachine').fadeIn().siblings('.ct-addUser').hide();
        }
    });
    //--------------------------------------------End Of Add User 2nd Tab---------------------------------------------

    //--------------------------------------------Start auto refresh when modal is hidden---------------------------------------------
    jQuery('#modal-edit-user-admin').on('hidden.bs.modal', function () {
        window.reload();
    });
    jQuery('#modal-edit-user-superAdmin').on('hidden.bs.modal', function () {
        //window.reload();
        location.reload();
        //jQuery('#both').empty();
    });
    //--------------------------------------------End auto refresh when modal is hidden---------------------------------------------

    //--------------------------------------------Start Of Add User Role DropDown 2nd Tab---------------------------------------------
    document.getElementById("create-selected-role").addEventListener('change', (event) => {
        var ifGoingToCreateNewSuperAdmin = document.getElementById('view-vendingmachine');
        var ifGoingToCreateNewAdmin = document.getElementById('view-store');
        var role = event.target.value;
        if(role == 1)
        {
            ifGoingToCreateNewSuperAdmin.style.visibility = 'visible';
            ifGoingToCreateNewAdmin.style.visibility = 'hidden';
        }
        else
        {
            alert('Check the second tab of this modal to select the store(s) that this account can manage.');
            ifGoingToCreateNewSuperAdmin.style.visibility = 'hidden';
            ifGoingToCreateNewAdmin.style.visibility = 'visible';
        }
    });
    //--------------------------------------------End Of Add User Role DropDown 2nd Tab---------------------------------------------

    //--------------------------------------------(user must be super admin)Start Of Edit Drop down---------------------------------------------
    document.getElementById("superAdmin-data-role-id").addEventListener('change', (event) => {

        //display tabs
        var ifGoingToUpdateToSuperAdmin = document.getElementById('vendingTabSuperAdmin');
        var ifGoingToUpdateToAdmin = document.getElementById('storeTabSuperAdmin');

        //display store list or vendingmachine list
        var displayVending = document.getElementById('roleSuperAdminDisplayVending');
        var displayStore = document.getElementById('changeRoleBackToAdminFromSuperAdmin');

        var role = event.target.value;

        if(role == 1)
        {
            ifGoingToUpdateToSuperAdmin.style.visibility = 'hidden';
            displayVending.style.visibility = 'hidden';
            ifGoingToUpdateToAdmin.style.visibility = 'hidden';
        }
        else
        {
            alert('Check the second tab of this modal to select the store(s) that this account can manage.');
            ifGoingToUpdateToSuperAdmin.style.visibility = 'hidden';
            displayStore.style.visibility = 'visible';
            ifGoingToUpdateToAdmin.style.visibility = 'visible';
        }
    });
    //--------------------------------------------(user must be super admin)End Of Edit Drop down---------------------------------------------

    jQuery(".tp-tooltip-delete").click(function() {
        var id = jQuery(this).attr('data-id');
        var delete_confirmation = jQuery('#modal-delete-confirmation');

        delete_confirmation.find('#id').val(id);
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
