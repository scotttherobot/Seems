$(function() {

   $("#addImageButton").click(function() {
      // There will only be a mediaManager on the page IFF the user is logged in.
      // If the user is not logged in, the $.post() request will fail.
      // If there is no gallery, the request will fail.
      if (App.mediaManager && App.gallery) {
         App.mediaManager.showMediaManager(function(mediaItem) {
            var data = { medid : mediaItem.medid };
            $.post('/0.1/media/galleries/' + App.gallery.id,  data, function (response) {
               console.log(response);
               if (response.errors.length) {
                  alert(response.errors[0]);
               } else {
                  // Add the element to the collection.
                  // Create an image element
                  var image = $('<img>')
                     .attr({src : response.gallery[response.gallery.length - 1].src,
                        alt : response.gallery[response.gallery.length - 1].caption});
                  // Add the image into a brick
                  var brick = $('<div class="brick">').append(image);
                  // Put the brick into the masonry container
                  $("#masonryContainer").append(brick);
                  $("#masonryContainer").masonry('appended', brick);
               }
            });
         });
      } else {
         console.log("Error! No media manager found on page!");
      }
   });

   /**
    * First, hide all the images 'cause they're gonna look terrible.
    * Then, when they've finished loading, unhide them and then arrange
    * them using masonry.
    */
   $("#masonryContainer").children().hide();
   $("#masonryContainer").imagesLoaded(function() {
      $("#masonryContainer").children().show();
      $("#masonryContainer").masonry({
         columnWidth: 230,
         itemSelector: '.brick'
      });
   });
});
