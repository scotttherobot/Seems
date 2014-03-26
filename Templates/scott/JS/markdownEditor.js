$(function() {

   var updatePost = function (data) {
      console.log(data);
      $.post('/0.1/content/' + App.post.id, data, function (response) {
         console.log(response);
         if (response.errors.length) {
            alert("Error! " + response.errors[0]);
         } else {
            alert("Success!");
         }
      });
   };

   var editor = CodeMirror.fromTextArea($("#bodyInput")[0], {
      mode: "text/x-markdown",
      lineNumbers: true
   });

   editor.setSize(null, 500);

   $("#undoButton").click(function() {
      editor.undo();
   });
   $("#redoButton").click(function() {
      editor.redo();
   });
   $("#insertPhotoButton").click(function() {
      alert("prompt for a photo blah blah");
      var image = "![selfie](http://scott.soysauce.land/media/1_531fc7dbd8bf0_photocopy3.JPG)";
      editor.replaceRange(image, editor.getCursor(), null);
   });


   $("#saveButton").click(function() {
      var bodyString = editor.getValue();
      var data = { body : bodyString };
      updatePost(data);
   });

});
