jQuery(document).ready(function () {
    jQuery('.ct-serial__container').hide();

    jQuery("input[name='radio-edit-type']").change(function() {
        const editDetailsType = jQuery("input[name='radio-edit-type']:checked").val();

        if (editDetailsType === 'radio-edit-slot') {
            jQuery('.ct-slot__container').fadeIn().siblings('.ct-serial__container').hide();
        } else if (editDetailsType === 'radio-edit-serial') {
            jQuery('.ct-serial__container').fadeIn().siblings('.ct-slot__container').hide();
        }
    });

    jQuery(".float-right").click(function() {        
        jQuery('#modal-add-machine-slot').find('#current_count').on("input", function() {
            var numDuplicates = parseInt(jQuery(this).val());
            var duplicatedHtml = "";
            for (var i = 0; i < numDuplicates; i++) {
                duplicatedHtml += generateSerial(i, "");
            }
            jQuery('#modal-add-machine-slot').find(".ct-serial__container").empty().append(duplicatedHtml);
        });

        // get current value of the field                  
        let maxField = document.querySelector('#max-quantity');              
        let currentField = document.querySelector('#current_count');

        // resets both max and current field.
        maxField.addEventListener("click", function(){
            currentField.value = maxField.value = "";
        });

        // resets current field only.
        currentField.addEventListener("click", function(){            
            if(maxField.value == "")
            {        
                // maxField.style.backgroundColor = "red"; 
                document.getElementById("max-quantity").style.border = "3px solid red"       
                document.getElementById("max-quantity").select();
            }  
            else if(maxField.value != "")
            {
                currentField.value = "";
            }
            else
            {
                currentField.value = maxField.value = "";
            }                    
        });

        maxField.addEventListener("change", (Max) => {                                                     
            // maxField.style.backgroundColor = "white";
            document.getElementById("max-quantity").style.border = ""
        });        

        currentField.addEventListener("change", (current) => {
        if(maxField.value == "")
        {
            currentField.value = maxField.value = "";
            document.getElementById("max-quantity").select();
        }                                                                                             
        else
        {                                             
            // max is higher             
            if(Number.parseInt(maxField.value) < Number.parseInt(currentField.value))
            {                
                // alert("1st if");
                return currentField.value = maxField.value;
            }            
            // current is higher                       
            else// if(maxField.value > currentField.value && !(maxField.value < currentField.value))
            {
                // alert("2nd if");                                                                       
                return currentField.value = current.target.value;
            } 
        }                      
        });     
    });

    // if current qty, is higher than max qty. current qty field will autoamtically update, and its value will be equal to max qty

    jQuery(".tp-tooltip-edit-spare").click(function() {        
        var spareQuantity = jQuery(this).attr('data-spare-quantity');
        var currentQuantity = jQuery(this).attr('data-current-quantity');
        var id = jQuery(this).attr('data-product-id');
        
        var availableSpareParts = spareQuantity - currentQuantity;        
        var edit_spare_qty = jQuery('#modal-edit-spare-qty');
        edit_spare_qty.find('#totalSpareParts').val((availableSpareParts < 0 ? availableSpareParts * -1 : availableSpareParts));
        edit_spare_qty.find('#id').val(id);
        edit_spare_qty.find('#currentQry').val(currentQuantity);

        jQuery('#modal-edit-spare-qty').find('#totalSpareParts').on("click", function() {
            jQuery('#modal-edit-spare-qty').find('#totalSpareParts').val("");
        });  
    });

    jQuery(".tp-tooltip-download").click(function() {
        var row = jQuery(this).closest("tr");
        var id = row.find('.machine-slot-id').html();
        var productName = row.find('.product-name').html();
        var currentCount = row.find('.current-count').html();
        var maxCount = row.find('.max-count').html();
        var spareQuantity = row.find('.reserve-count').html();
        var stockAlert = jQuery(this).attr('data-stock-alert');
        var serial = jQuery(this).attr('data-serial');

        jQuery('#modal-slot-details').find('#machine-slot-id').html(id);
        jQuery('#modal-slot-details').find('#product-name').html(productName);
        jQuery('#modal-slot-details').find('#current-count').html(currentCount);
        jQuery('#modal-slot-details').find('#max-count').html(maxCount);
        jQuery('#modal-slot-details').find('#spare-quantity').html(spareQuantity);
        jQuery('#modal-slot-details').find('#stock-alert').html(stockAlert);
        jQuery('#modal-slot-details').find('#serial').html(serial);
    });
    
    jQuery(".tp-tooltip-manage").click(function() {
        var row = jQuery(this).closest("tr");
        var id = row.find('.machine-slot-id').html();
        var productId = jQuery(this).attr('data-product-id');
        var machineSlotIndex = jQuery(this).attr('data-machine-slot-index');
        var currentCount = row.find('.current-count').attr('data-current-count');
        var maxCount = row.find('.max-count').attr('data-max-count');
        var stockAlert = jQuery(this).attr('data-stock-alert');
        var spareQuantity = jQuery(this).attr('data-spare-quantity');
        var serial = jQuery(this).attr('data-serial');          
                        
        // alert(maxCount);    

        jQuery('#modal-edit-slot').find('#machine-slot-id').val(id);
        jQuery('#modal-edit-slot').find('#index').val(machineSlotIndex);
        jQuery('#modal-edit-slot').find('#product-id').val(productId);
        jQuery('#modal-edit-slot').find('#max-count').val(maxCount);
        jQuery('#modal-edit-slot').find('#current-count').val(currentCount);
        jQuery('#modal-edit-slot').find('#spare-quantity').val(spareQuantity);
        jQuery('#modal-edit-slot').find('#stock_alert').val(stockAlert);
        jQuery('#modal-edit-slot').find('#serial').val(serial);    
        
        var splittedSerial = serial.split(",");
        var duplicatedHtml = "";

        for (var i = 0; i < splittedSerial.length; i++) {
            duplicatedHtml += generateSerial(i, splittedSerial[i]);
        }
        jQuery('#modal-edit-slot').find(".ct-serial__container").empty().append(duplicatedHtml);

        // when clicked, empty field.
        // jQuery('#modal-edit-slot').find('#current-count').on("click", function() {
        //     jQuery('#modal-edit-slot').find('#current-count').val("");
        // });                

        jQuery('#modal-edit-slot').find('#current-count').on("change", function(e) {            
            var conditionalValue = Number.parseInt(e.target.value) > Number.parseInt(maxCount) ? maxCount : e.target.value;
            jQuery('#modal-edit-slot').find('#current-count').val(conditionalValue);
            var numDuplicates = parseInt(e.target.value);
            var duplicatedHtml = "";
    
            for (var i = currentCount; i < numDuplicates; i++) {
                duplicatedHtml += generateSerial(i, "");
            }
            // jQuery('#modal-edit-slot').find(".ct-serial__container").empty().append(duplicatedHtml);
            jQuery('#modal-edit-slot').find(".ct-serial__container").append(duplicatedHtml);    
            
           
            jQuery('#modal-edit-slot').addEventListener('hidden.bs.modal', function(event) {            
                jQuery(this).val() = 0;
                console.log("event" + jQuery(this).val());
            }); 
        });

        // jQuery("#submit_edit_button").click(function(e) {
        //     alert(serial);
            
        //     // alert('update-clicked');            
        // })

        // jQuery("#serial_tab_page").click(function(e) {
        //     // document.getElementById("serial_tab_page");
        //     alert('serial_page_clicked');            
        // });
        

        // this is to fixed the current quantity.
        // jQuery('#modal-edit-slot').on('hidden.bs.modal', function (e) {            
        //     // location.reload();            
        //     // history.go(0);
        // });                
    });

   

    function generateSerial(id, value) {
        return `<div class="row align-items-center mt-2">
            <div class="col-1">` + (id + 1) + `.</div>
            <div class="col-9 px-0">
            <input type="text" id="serial[]" name="serial[]"
                placeholder="Enter Serial No." class="form-control" value="` + (value) + `"
                onkeyup="this.value = this.value.toUpperCase();" required>
            </div>
        </div>
        `;
      }

    jQuery(".tp-tooltip-delete").click(function() {
        var id = jQuery(this).attr('data-id');
        var delete_confirmation = jQuery('#modal-delete-confirmation');

        delete_confirmation.find('#id').val(id);
    });

    tippy('.tp-tooltip-download', {
        content: 'View Slot Details',
        theme: 'custom',
        arrow: false,
    });
    tippy('.tp-tooltip-manage', {
        content: 'Edit',
        theme: 'custom',
        arrow: false,
    });

});
