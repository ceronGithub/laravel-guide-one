// var welcomeVidDropzone = document.querySelector("#welcome-vid-dropzone");
var previewNode = document.querySelector("#dz-preview-template");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
jQuery('#dz').hide();

var welcomeVidDropzone = new Dropzone("#welcome-vid-dropzone", {
    previewTemplate: previewTemplate,
    previewsContainer: "#dz-preview-container",
    maxFiles: 1,
    acceptedFiles: ".mp4",
    paramName: "video",
});

jQuery(".btn-danger").click(function() {
    jQuery('#dz').show();
    jQuery('#dz-preview-placeholder-container').hide();
    // document.querySelector("#dz-preview-placeholder-container").remove();
});

welcomeVidDropzone.on("addedfile", function(file) {
    // Set the name for the file
    file.customName = "temp.mp4";
    jQuery('#dz').hide();
    jQuery('#dz-preview-template').show();

  });

welcomeVidDropzone.on("removedfile", function(file) {
    jQuery('#dz').show();
    jQuery('#dz-preview-template').hide();

    // // Remove the hidden form field when file is removed
    // if (file.previewElement && file.previewElement.parentNode) {
    //   file.previewElement.parentNode.removeChild(file.previewElement);
    // }
});
