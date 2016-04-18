/**
 * Created by Adam on 10/04/16.
 */

$(document).ready(function(){
    $("#submit").click(function(){
        $.ajax({
            url: "/api/user/login/",
            method: "POST",
            data: { email: $("#email").value, password: $("#password").value },
            statusCode: {
                200: function()
                {
                    //do something
                    alert("This shows this is fired if the response works");
                },
                404: function()
                {
                    alert("If this fires there is a problem");
                }
            }
        })
            .done(function()
            {
                $("#status").text("done");
                $("#loginForm").toggle();
            })
            .error(function()
            {
                $("#status").text("error");
            });


    });
});