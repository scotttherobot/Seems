$(function() {

   Webcam.attach("#viewfinder");

   $("#release").click(function() {
      var data_uri = Webcam.snap();
      $('#preview').attr('src', data_uri);
   });

   $("#save").click(function() {
      var data_uri = $("#preview").attr('src');
      Webcam.upload( data_uri, '/0.1/media/', function (code, text) {
         alert(code + " " + text);
      });
   });


});
