function formAnimate(){
    $(".input-light1").on("click", function(){
        $(".bot-border").css({"width" : "100%", "transition" : "1.5s"});
        $("#username-label").css({"top": "0px", "transition": "0.5s"});
    });

    $(".input-light1").on("input", function(){
        $(".bot-border").css({"width" : "100%", "transition" : "1.5s"});
        $("#username-label").css({"top": "0px", "transition": "0.5s"});
    });
    $(".input-light1").on("blur", function(){
        $(".bot-border").css("width", "0%");
        if( $(".input-light1").val() == ''){
            $("#username-label").css({"top": "30px", "transition": "0.5s"});
        }else{
            $(".bot-border").css("width", "100%");
        }
    });
    $(".input-light2").on("click", function(){
        $(".bot-border2").css({"width" : "100%", "transition" : "1.5s"});
        $("#password-label").css({"top": "0px", "transition": "0.5s"});
    });
    $(".input-light2").on("input", function(){
        $(".bot-border2").css({"width" : "100%", "transition" : "1.5s"});
        $("#password-label").css({"top": "0px", "transition": "0.5s"});
    });
    $(".input-light2").on("blur", function(){
        $(".bot-border2").css("width", "0%");
        if( $(".input-light2").val() == ''){
            $("#password-label").css({"top": "30px", "transition": "0.5s"});
        }else{
            $(".bot-border2").css("width", "100%");
        }
    });

    if($(".input-light1").val() != '')
    {
        $(".bot-border").css({"width" : "100%", "transition" : "1.5s"});
        $("#username-label").css({"top": "0px", "transition": "0.5s"});
    }
}
