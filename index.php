<?php
    require_once 'base_function.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cutie for Mommy</title>
        <link rel="stylesheet" href="css/foundation.css">

        <!-- This is how you would link your custom stylesheet -->
        <link rel="stylesheet" href="cfm.css">
        <link href='http://fonts.googleapis.com/css?family=Coming+Soon' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Crafty+Girls' rel='stylesheet' type='text/css'>
        <script src="js/vendor/modernizr.js"></script>
        <script src="js/vendor/jquery.js"></script>

        <!-- background carousel -->
        <script src="js/vendor/carousel/jquery.velocity.min.js"></script>
        <script src="js/vendor/carousel/jquery.touchSwipe.min.js" type="text/javascript" ></script>
        <script src="js/vendor/carousel/bgcarousel.js" type="text/javascript"></script>
        <script type="text/javascript">
            var firstbgcarousel=new bgCarousel({
                wrapperid: 'mybgcarousel', //ID of blank DIV on page to house carousel
                imagearray: [
                  ['product/full.jpg', '<h2>Autumn Day</h2>The sun peaks through the trees, a knife that cuts through the chill, crisp air.'], //["image_path", "optional description"]
                  ['product/large.jpg', '<h2>Wind Chime</h2>The bellweather of the sky, the chime speaks of impending turmoil.'],
                  ['product/combo1.jpg', 'The scent of spring invigorates her as she inhales whilst the warm breeze brings a wave of tranquility.'],
                  ['product/andrew.jpg', 'Alone and Lonliness- Peace and Inner Struggle'] //<--no trailing comma after very last image element!
                ],
                displaymode: {type:'auto', pause:3000, cycles:0, stoponclick:false, pauseonmouseover:true},
                navbuttons: ['img/left.gif', 'img/right.gif', 'img/up.gif', 'img/down.gif'], // path to nav images
                activeslideclass: 'selectedslide', // CSS class that gets added to currently shown DIV slide
                orientation: 'h', //Valid values: "h" or "v"
                persist: true, //remember last viewed slide and recall within same session?
                slideduration: 500 //transition duration (milliseconds)
            })
        </script>
        <script src="cfm.js"></script>
    </head>
    <body>
        <!--
        <div class='showcase'>
            <div id='p1' class='product'></div>
            <div id='p2' class='product'></div>
            <div id='p3' class='product'></div>
            <div id='p4' class='product'></div> 
        </div>
        -->
        <div id="mybgcarousel" class="bgcarousel"></div>
        <div id='price-section' class='wrapper'>
            <div class='row'>
                <div class='small-8 columns small-centered'>
                    <div class='row'>
                        <div class='small-6 columns'>
                            <ul class="pricing-table">
                                <li class="title">Single Treat</li>
                                <li class="price">$14.99</li>
                                <li class="description">Make the cutest Cuter!</li>
                                <li class="bullet-item">5 Handpicked Accessories</li>
                                <li class="bullet-item">One Happy Mommy</li>
                                <li class="cta-button"><a class="button info" href="#">Buy Now</a></li>
                            </ul>
                        </div>
                        <div class='small-6 columns'>
                            <ul class="pricing-table">
                                <li class="title">Subscribe</li>
                                <li class="price">$14.99</li>
                                <li class="description">Get a surprise every 2 months!</li>
                                <li class="bullet-item">5 Handpicked Accessories</li>
                                <li class="bullet-item">One Happy Mommy, all the time!</li>
                                <li class="cta-button"><a class="button success" href="#">Buy Now</a></li>
                            </ul>
                        </div>     
                    </div>
                </div>
            </div>
        </div>
        
        <div id='footer'>
            <div class='row'>
                <div class='small-12 columns'>
                    <ul id='footer-menu'>
                        <li><a href='#'>Contact us</a></li>
                        <li><a href='#'>How it works</a></li>
                        <li><a href='#'>FAQ</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </body>
</html>