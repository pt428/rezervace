(function($){
    $(function(){
    /* Scroll to sections */
    $(".jq--scroll-form").click(function(){
    $("html, body").animate({scrollTop: $(".jq--form").offset().top}, 200);
    });
    });
    })(jQuery);

     document.querySelector(".btn-edit-schvalit").addEventListener("click",(e)=>{
        // document.querySelector(".form-edit").setAttribute("class","hidden")
        console.log("asdf");
     });