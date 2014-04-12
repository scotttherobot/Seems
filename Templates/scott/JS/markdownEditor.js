$(function() {

   var editor = CodeMirror.fromTextArea($("#bodyInput")[0], {
      mode: "text/x-markdown",
      lineNumbers: true
   });

   editor.setSize(null, 500);

   var updatePost = function (data) {
      $.post('/0.1/content/' + App.post.id, data, function (response) {
         console.log(response);
         if (response.errors.length) {
            alert("Errors: " + response.errors.join(" ")); 
         } else {
            alert("Success!");
         }
      });
   };

   var createPost = function (data) {
      $.post('/0.1/content/', data, function (response) {
         console.log(response);
         if (response.errors.length) {
            alert("Errors: " + response.errors.join(" ")); 
         } else {
            window.location.href = "/blog/" + response.id;
         }
      });
   };

   $("#undoButton").click(function() {
      editor.undo();
   });
   $("#redoButton").click(function() {
      editor.redo();
   });
   $("#insertPhotoButton").click(function() {
      App.mediaManager.showMediaManager(function(response) {
         var image = "!["
            + response.fname
            + "]("
            + response.src
            + ")";
         editor.replaceRange(image, editor.getCursor(), null);
      });
   });

   if (App.post) {
      $("#saveButton").click(function() {
         var titleString = $.trim($("#postTitle").text());
         var bodyString = $.trim(editor.getValue());
         var leaderMedid = $.trim($("#leaderImage").attr('data-medid'));
         var publishedChecked = $("#publishedCheckbox").is(':checked') ? 1 : 0;
         var data = { 
            title : titleString
            , body : bodyString
            , leader: leaderMedid
            , published: publishedChecked
         };
         updatePost(data);
      });
   } else {
      $("#saveButton").click(function() {
         var titleString = $.trim($("#postTitle").text());
         var bodyString = $.trim(editor.getValue());
         var leaderMedid = $.trim($("#leaderImage").attr('data-medid'));
         var publishedChecked = $("#publishedCheckbox").is(':checked') ? 1 : 0;
         var data = { 
            title : titleString
            , body : bodyString
            , published: publishedChecked
            , leader: leaderMedid };
         createPost(data);
      });
   }

   $("#changeLeaderButton").click(function() {
      App.mediaManager.showMediaManager(function(response) {
         $("#leaderImage").attr('data-medid', response.medid);
         $("#leaderImage").attr('src', response.src);
      });
   });

   $("#postTitle").click(function() {
      var title = prompt("Title", $("#postTitle").text());
      $("#postTitle").text(title);
   });

});
