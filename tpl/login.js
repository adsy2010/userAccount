/**
 * Created by Adam on 10/04/16.
 */

$(document).ready(function(){
    $("#signin").click(function(){
        //alert("Clicked");
        var xmlData;
        $.ajax({
            url: "/api/user/login/",
            type: 'POST',
            data: {
                email: $("#email").val(),
                password: $("#password").val()
            },
            success: function(data){
                xmlData = data;
                console.log(data);
            },
            error: function(data)
            {
                console.log(data);
            }
        })
            .success(function(xmlData)
            {
                //var xmlDoc = $.parseXML(xmlData),
                //    xmlData = $(xmlDoc),
                var $name = $(xmlData).find("name").text();

                $("#status").text("Logged in successfully.").css('color', 'green');
                $("#userGreeting").text("Welcome "+ $name); //edit this
                $(".loginForm").hide();
            })
            .error(function()
            {
                $("#status").text("Incorrect username or password.").css('color', 'red');
            });


    });
});