$(document).ready(function(){
  $('#price-box-1').click(function(){
    var link_url = 'http://www.amazon.com/Cute-Hair-Clips-Little-Girls/dp/B00SQQ3QUQ/ref=sr_1_2?ie=UTF8&qid=1423789538&sr=8-2&keywords=cutieformommy';
    window.open(link_url);
  });

  $('#price-box-2').click(function(){
    var link_url = 'http://www.amazon.com/Cute-Accessories-Little-Girls-Surprise-set/dp/B00SSOTXSA/ref=sr_1_1?ie=UTF8&qid=1423789538&sr=8-1&keywords=cutieformommy';
    window.open(link_url);
  });

  // $('.bxslider').bxSlider({
  //   minSlides: 1,
  //   auto:true,
  //   infiniteLoop: true,
  //   //adaptiveHeight: true,
  //   responsive: true,
  //   maxSlides: 3,
  //   slideWidth: 400,
  //   pager: false,
  //   slideMargin: 20
  // });

  slidr.create('products', {
    // breadcrumbs: true,
    // controls: 'corner',
    // direction: 'vertical',
    fade: false,
    keyboard: true,
    overflow: true,
    pause: true,
    theme: '#222',
    timing: { 'cube': '0.5s ease-in' },
    touch: true,
    transition: 'cube'
  }).add('h',['a','b','c','d','e','f','g','h','i','j','k','l','m','a'],'cube').auto();

});
