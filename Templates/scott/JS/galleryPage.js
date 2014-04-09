$(function() {

   $("#addImageButton").click(function() {
      App.mediaManager.showMediaManager(function(mediaItem) {
         var data = { medid : mediaItem.medid };
         $.post('/0.1/media/galleries/' + App.gallery.id,  data, function (response) {
            console.log(response);
            if (response.errors.length) {
               alert(response.errors[0]);
            } else {
               // Add the element to the collection.
               var image = $('<img>')
                  .attr({src : response.gallery[response.gallery.length - 1].src,
                     alt : response.gallery[response.gallery.length - 1].caption});
               var brick = $('<div class="brick">').append(image);
               $("#masonryContainer").append(brick);
               $("#masonryContainer").masonry('appended', brick);
            }
         });
      });
   });

   $("#masonryContainer").children().hide();
   $("#masonryContainer").imagesLoaded(function() {
      console.log("Images loaded!");
      $("#masonryContainer").children().show();
      $("#masonryContainer").masonry({
         columnWidth: 230,
         itemSelector: '.brick'
      });
   });
});
