$(window).scroll(function(){
  
  var top = $(window).scrollTop();
  
  if(top > 0){
    $('#thirteen05-navbar').css('background-color','rgba(0,0,0,0.8)');
  } else{
    $('#thirteen05-navbar').css('background-color','rgba(0,0,0,0)');
  }
  
});

$('').mouseenter();