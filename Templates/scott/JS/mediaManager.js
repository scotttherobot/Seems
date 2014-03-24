$(function() {

   $("#mediaList").masonry({ 
      columnWidth: 200,
      itemSelector: ".mediaItem"
   });

   $.get("/0.1/media", function( data ) {
      if (data.errors.length != 0) {
         alert("There was an error loading the media manager.");
      } else {
         var media = data.media;
         //var added = [];
         //var fragment = document.createDocumentFragment();
         $.each(media, function(index, mediaItem) {
            //$("#mediaList").append("<li>" + mediaItem.medid + "</li>");
            var imageBlock = $("#mediaTemplate").clone();
            imageBlock.attr("id", "medid_" + mediaItem.medid);
            imageBlock.attr("data-medid", mediaItem.medid);
            imageBlock.children("#name").text(mediaItem.medid);
            imageBlock.children("#thumbnail").attr("src", mediaItem.src);
            imageBlock.removeClass("hide");

            $("#mediaList").append(imageBlock);
            $("#mediaList").masonry('appended', imageBlock);
         });
      }
   });


});
