$(function() {

   Webcam.attach("#viewfinder");

   $("#release").click(function() {
      var data_uri = Webcam.snap();
      $('#preview').attr('src', data_uri);
   });

   $("#save").click(function() {
      var data_uri = $("#preview").attr('src');
      Webcam.upload( data_uri, '/0.1/media/selfie/', function (code, response) {
         var response = $.parseJSON(response);
         if (response.errors.length) {
            alert("Errors: " + response.errors.join(" ")); 
         } else {
            alert("Success! Your selfie was added to the gallery!");
         }
      });
   });


});
