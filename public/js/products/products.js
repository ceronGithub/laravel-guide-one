jQuery(document).ready(function () {
    jQuery(".ct-product").click(function() {
        //get data
        var product_id = jQuery(this).attr('data-id');
        var product_code = jQuery(this).attr('data-product-code');
        var name = jQuery(this).attr('data-name');
        var price = jQuery(this).attr('data-price');
        var spec = jQuery(this).attr('data-spec');
        var feature = jQuery(this).attr('data-feature');
        var desc = jQuery(this).attr('data-desc');
        var categ = jQuery(this).attr('data-categ');
        var imagePaths = jQuery(this).attr('data-image');
        //refresh the form, and to avoid duplications of Images
        jQuery('#imageContainer').empty();
        var div = document.getElementById('imageContainer');
        var imageID;//variable for image deletion
        for(x = 0; x < JSON.parse(imagePaths).length; x++){
            if(JSON.parse(imagePaths).length > 1)
            {
                div.innerHTML +=
                `<label for="new-image" class="file-upload__card ct-product__upload"  >
                    <!-- File Preview Image-->
                    <img class="file-upload__preview product-image-${x}" src="#" alt="image">
                    <div class="file-upload__overlay">
                        <!-- X button -->
                        <div class="file-upload__delete">
                            <button type="button" class="btn btn-round btn-sm ct-btn-xs delete_image_function"
                            id = "imageIdDelete-${x}"
                            data-removeImage="${x}"><i class="fe fe-x"></i></button>
                        </div>
                    </div>
                </label>`;
            }
            else if(JSON.parse(imagePaths).length == 1)
            {
                div.innerHTML +=
                `<label for="new-image" class="file-upload__card ct-product__upload"  >
                    <!-- File Preview Image-->
                    <img class="file-upload__preview product-image-${x}" src="#" alt="image">
                    </div>
                </label>`;
            }

            var imagetrimmedPath = JSON.parse(imagePaths)[x];
            jQuery('.product-image-' + x).attr('src', imagetrimmedPath);
            //jQuery('#imageIdDelete-' + x).attr('data-removeImage-'+x, x);
        }
        //display details
        jQuery('#modal-edit-product').find('#product-id').val(product_id);
        jQuery('#modal-edit-product').find('#product-code').val(product_code);
        jQuery('#modal-edit-product').find('#productId').val(product_code);
        jQuery('#modal-edit-product').find('#product-name').val(name);
        jQuery('#modal-edit-product').find('#price').val(price);
        jQuery('#modal-edit-product').find('#specification').val(spec);
        jQuery('#modal-edit-product').find('#feature').val(feature);
        jQuery('#modal-edit-product').find('#description').val(desc);
        jQuery('#modal-edit-product').find('#category-id').val(categ);
        // jQuery(document).on("click", ".delete_image_function", function(event) {
        //     //prevent page from refreshing
        //     event.preventDefault();
        //     imageID = jQuery(this).attr('data-removeImage');
        //     jQuery('#deleteImage').val(imageID);
        // });
        jQuery("#submitUpdateFormBtn").click(function(event) {
            jQuery('.updateForm').submit();
        });
    });

    jQuery(".for_filter").click(function(event) {
        var categoryNumber = jQuery(this).attr('data-CategoryName');
        jQuery('#getCategory').val(categoryNumber);
        event.preventDefault();
        document.getElementById('filter').submit();
    });

    

    jQuery(document).on("click", ".delete_image_function", function(e) {
        e.preventDefault();

        var product_id = jQuery('#product-id').val();    
        var imagePaths = jQuery(this).attr('data-image');     

        // url 
        var url = "/products/AjaxUpdate/" + ID;

        // get value from blade element/s    
        var ID = jQuery('#userID').val();   
        var name = jQuery('#product-name').val();
        var desc = jQuery('#description').val();                
        var categoryId = jQuery('#category-id').val();                
        var pCode = jQuery('#product-code').val();
        var removeImage = jQuery(this).attr('data-removeImage');    

        // pass value to blade element/s
        //jQuery('#deleteImage').val(removeImage);               
        //document.getElementById('delete').submit();    

        jQuery('#imageContainer').empty();        

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            type : 'POST',
            url : url,            
            data : {
                'userID' : ID,
                'product-name' : name,
                'description' : desc,                
                'category-id' : categoryId,
                'productCode' : pCode,
                'deleteImage' : removeImage,                
            },                                    
            success: function(data)
            {
                var remainingImage = data.products;

                const result = [];
                const images = [];   
                const trimImage = [];

                remainingImage.forEach((item, index) => {                        
                    if(product_id == item.id)
                    {
                        result.push(item);
                        images.push(item.image);   
                        for (let i = 0; i <= item.image.length-1; i++) {                            
                            //trimImage[i] = item.image[i];
                            if(item.image.length > 1)
                            {
                                jQuery('#imageContainer').append(
                                    `<label for="new-image" class="file-upload__card ct-product__upload"  >
                                        <!-- File Preview Image-->
                                        <img class="file-upload__preview product-image-${i}" src="${item.image[i]}" alt="image">
                                        <div class="file-upload__overlay">
                                            <!-- X button -->
                                            <div class="file-upload__delete">
                                                <button type="button" class="btn btn-round btn-sm ct-btn-xs delete_image_function"
                                                id = "imageIdDelete-${i}"
                                                data-removeImage="${i}"><i class="fe fe-x"></i></button>
                                            </div>
                                        </div>
                                    </label>`
                                );                   
                            }
                            else
                            {
                                jQuery('#imageContainer').append(
                                    `<label for="new-image" class="file-upload__card ct-product__upload"  >
                                        <!-- File Preview Image-->
                                        <img class="file-upload__preview product-image-${i}" src="${item.image[i]}" alt="image">                                        
                                    </label>`
                                );                   
                            }
                        }                                                                                               
                    }                       
                });                
                                
                console.log(result);
                console.log(images);   
                console.log(trimImage);                
                console.log(remainingImage);
            }
        });
    });
    /*
    function forFilter(CategoryID)
    {
        jQuery('#getCategory').val(CategoryID);
        event.preventDefault();
        document.getElementById('filter').submit();
    }
    function deleteFunction(imageID){
        jQuery('#deleteImage').val(imageID);
        event.preventDefault();
        document.getElementById('delete').submit();
    }

    */
    jQuery(function () {
        // register plugins
        jQuery.fn.filepond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation
        );

        jQuery('.input--filepond').filepond({
            allowMultiple: true,
            labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            imagePreviewHeight: 160
        });
    });

});
