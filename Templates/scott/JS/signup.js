$(function() {

   $("#signupForm").submit(function(e) {
      e.preventDefault();
      var data = $(this).serialize();
      $.post('/0.1/register/', data, function(response) {
         if(response.errors.length) {
            alert("Errors: " + response.errors.join(" "));
         } else {
            $("#signupForm").trigger("reset");
            console.log(response);
            alert("You have successfully registered.");
            window.location.href = "/login/";
         }
      });
   });

});
