$(function() {

   new Medium({
      element: document.getElementById('postTitle'),
      mode: 'partial',
      placeholder: "Title"
   });

   /*
   new Medium({
      element: document.getElementById('post-well'),
      mode: 'rich',
      placeholder: 'Let\'s write!'
   });
   */

   $("#post-well").notebook();
   // Using jQuery:
   $('#post-well').on('contentChange', function(e) {
          var content = e.originalEvent.detail.content;
          //console.log(content);
   });

   var updatePost = function(data) {
      console.log(data);
      $("#xMark").addClass("hide");
      $("#checkMark").addClass("hide");
      $.post('/0.1/content/' + App.post.id, data, function ( response ) {
         console.log(response);
         if (response.errors.length) {
            $("#xMark").removeClass("hide");
         } else {
            $("#checkMark").removeClass("hide");
         }
      });
   };

   var createPost = function(data) {
      console.log(data);
      $("#xMark").addClass("hide");
      $("#checkMark").addClass("hide");
      $.post('/0.1/content/', data, function ( response ) {
         console.log(response);
         if (response.errors.length) {
            $("#xMark").removeClass("hide");
         } else {
            $("#checkMark").removeClass("hide");
            window.location.href = "/blog/" + response.id;
         }
      });
   };

   // If we are editing an existing post, we can turn on
   // AJAX for the 'published' button.
   if (App.post) {
      $("#publishedCheckbox").change(function() {
         var data = { published : $("#publishedCheckbox").is(':checked') ? 1 : 0 };
         updatePost(data);
      });

      $("#saveButton").click(function() {
         var titleString = $.trim($("#postTitle").text());
         var bodyString = $.trim($("#post-well").html());
         var data = { title : titleString, body : bodyString };
         updatePost(data);
      });
   } else {
   // In this case we're creating a new post from scratch.
   // After we get the id back, redirect to the /edit/id page
      $("#saveButton").click(function() {
         var titleString = $.trim($("#postTitle").text());
         var bodyString = $.trim($("#post-well").html());
         var publishedChecked = $("#publishedCheckbox").is(':checked') ? 1 : 0;
         var data = { title : titleString, body : bodyString, published : publishedChecked };
         createPost(data);
      });

   }

});
