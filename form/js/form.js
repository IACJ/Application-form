$(document).ready(function(){

  $(".leave").focus(function(){
    $(this).prev().css("color","#e9ba2e");
    $(this).parent().parent().css("border-color","#e9ba2e");
  });

  $(".leave").blur(function(){
    $(this).prev().css("color","#7d5f06");
    $(this).parent().parent().css("border-color","#7d5f06");
  });

  $(".leave1").focus(function(){
    $(this).parent().prev().css("color","#e9ba2e");
    $(this).parent().css("border-color","#e9ba2e");
  });

  $(".leave1").blur(function(){
    $(this).parent().prev().css("color","#7d5f06");
    $(this).parent().css("border-color","#7d5f06");
  });  

  $(".leave2").focus(function(){
    $(this).parent().prev().children().css("color","#e9ba2e");
    $(this).parent().css("border-color","#e9ba2e");
  });

  $(".leave2").blur(function(){
    $(this).parent().prev().children().css("color","#7d5f06");
    $(this).parent().css("border-color","#7d5f06");
  });

  $(".leave3").focus(function(){
    $(this).prev().css("color","#e9ba2e");
    $(this).css("border-color","#e9ba2e");
  });

  $(".leave3").blur(function(){
    $(this).prev().css("color","#7d5f06");
    $(this).css("border-color","#7d5f06");
  });

});

function choose(t){
    if(t.value!=""){
        t.style.color="black";
    }
    else{
        t.style.color="rgba(153,153,153,0.5)";
    }
}

function setToken(){

    $.get("token.php",function(data,status){var t;
        if (status=="success"){                    
                    t = data;            
                } 
                else{               
                    t = "";              
                }
                document.getElementById('token').value=t;
    });
}

window.onload=setToken();