$(function() {

   App.mediaManager = {
      showMediaManager: function (callback) {
         App.imageSelected = callback;
         $("#mediaModal").reveal({
            animation: 'fadeAndPop',
            animationspeed: 300,
            closeonbackgroundclick: true,
            dismissmodalclass: 'close-reveal-modal'
         });
      },
      injectImage: function (mediaItem) {
         var imageBlock = $("#mediaTemplate").clone();
         imageBlock.attr("id", "medid_" + mediaItem.medid);
         imageBlock.attr("data-medid", mediaItem.medid);
         imageBlock.children("#thumbnail").attr("src", mediaItem.src);
         imageBlock.click(function() {
            $("#mediaModal").trigger("reveal:close");
            App.imageSelected(mediaItem);
         });
         $("#mediaGrid").append(imageBlock);
      },
      uploadFiles: function (files, callback) {
         var data = new FormData();
         $.each(files, function(i, file) {
            data.append('file-' + i, file);
         });
         $.ajax({
            url: '/0.1/media/',
            data: data,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
               if (response.errors.length != 0) {
                  alert(response.errors[0]);
               } else {
                  $.each(response.uploaded, function (i, mediaItem) {
                     App.mediaManager.injectImage(mediaItem);
                  });
               }
            }
         });
      }
   };

   $.get("/0.1/media", function( data ) {
      if (data.errors.length != 0) {
         alert("There was an error loading the media manager.");
      } else {
         var media = data.media;
         //var added = [];
         //var fragment = document.createDocumentFragment();
         $.each(media, function(index, mediaItem) {
            App.mediaManager.injectImage(mediaItem);
         });
      }
   });

   $("#uploadButton").click(function() {
      $("#uploadFile").trigger('click');
   });

   $("#uploadFile").change(function () {
      App.mediaManager.uploadFiles(this.files, function(response) {

      });
   });
});
