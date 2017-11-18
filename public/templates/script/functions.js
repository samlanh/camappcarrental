jQuery(document).ready(function($) {

	'use strict';
    //***************************
    // Sticky Header Function
    //***************************
	  jQuery(window).scroll(function() {
	      if (jQuery(this).scrollTop() > 170){  
	          jQuery('body').addClass("automobile-sticky");
	      }
	      else{
	          jQuery('body').removeClass("automobile-sticky");
	      }
	  });

    //***************************
    // BannerOne Functions
    //***************************
      jQuery('.automobile-banner').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          infinite: true,
          dots: true,
          arrows: false,
          fade: true,
          responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
                {
                  breakpoint: 800,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 400,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
              ]
        });

    //***************************
    // BannerOne Functions
    //***************************
    jQuery('.automobile-footer-slider').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
        dots: false,
        arrows: false,
        responsive: [
              {
                breakpoint: 1024,
                settings: {
                  slidesToShow: 4,
                  slidesToScroll: 1,
                  infinite: true,
                }
              },
              {
                breakpoint: 800,
                settings: {
                  slidesToShow: 2,
                  slidesToScroll: 1
                }
              },
              {
                breakpoint: 400,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
                }
              }
            ]
      });
    //***************************
    // fixtureSlider Functions
    //***************************
      jQuery('.sportsmagazine-fixture-slider').slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          infinite: true,
          dots: false,
          prevArrow: "<span class='slick-arrow-left'><i class='fa fa-angle-left'></i></span>",
          nextArrow: "<span class='slick-arrow-right'><i class='fa fa-angle-right'></i></span>",
          responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
                {
                  breakpoint: 800,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 400,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
              ]
        });

    //***************************
    // Click to Top Button
    //***************************
    jQuery('.careplus-back-top').on("click", function() {
        jQuery('html, body').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
    //***************************
    // Parent AddClass Function
    //***************************
    jQuery(".sportsmagazine-dropdown-menu,.sportsmagazine-megamenu").parent("li").addClass("subdropdown-addicon");

    //***************************
    // Fancybox Function
    //***************************
    jQuery(".fancybox").fancybox({
      openEffect  : 'elastic',
      closeEffect : 'elastic',
    });

    //***************************
    // Countdown Function
    //***************************
    jQuery(function() {
        var austDay = new Date();
        austDay = new Date(austDay.getFullYear() + 1, 1 -1);
        jQuery('#automobile-comingsoon-countdown').countdown({
            until: austDay
        });
        jQuery('#year').text(austDay.getFullYear());
    });

    //***************************
    // CartToggle Function
    //***************************
    jQuery('.automobile-shoppingcart-btn').on("click", function(){
        jQuery('.automobile-cartbox').fadeToggle('slow');
        return false;
    });
    jQuery('html').on("click", function() { jQuery(".automobile-cartbox").fadeOut(); });

    //***************************
      // ThumbSlider Functions
      //***************************
      jQuery('.automobile-tabs-thumb').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.automobile-tabs-list'
          });
          jQuery('.automobile-tabs-list').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.automobile-tabs-thumb',
            dots: false,
            fade: false,
            prevArrow: false,
            nextArrow: false,
            centerMode: false,
            focusOnSelect: true,
            responsive: [
                  {
                    breakpoint: 1024,
                    settings: {
                      slidesToShow: 3,
                      slidesToScroll: 1,
                      infinite: true,
                      vertical: false,
                    }
                  },
                  {
                    breakpoint: 800,
                    settings: {
                      slidesToShow: 3,
                      slidesToScroll: 1,
                      vertical: false,
                    }
                  },
                  {
                    breakpoint: 400,
                    settings: {
                      slidesToShow: 2,
                      slidesToScroll: 1,
                      vertical: false,
                    }
                  }
                ],
          });

      //***************************
      // shop thumb Functions
      //***************************
      jQuery('.automobile-shop-thumb').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.automobile-shop-thumb-list'
          });
          jQuery('.automobile-shop-thumb-list').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.automobile-shop-thumb',
            dots: false,
            vertical: true,
            prevArrow: "<span class='slick-arrow-left'><i class='fa fa-angle-up'></i></span>",
            nextArrow: "<span class='slick-arrow-right'><i class='fa fa-angle-down'></i></span>",
            centerMode: false,
            focusOnSelect: true,
            responsive: [
                  {
                    breakpoint: 1024,
                    settings: {
                      slidesToShow: 3,
                      slidesToScroll: 1,
                      infinite: true,
                      vertical: false,
                    }
                  },
                  {
                    breakpoint: 800,
                    settings: {
                      slidesToShow: 3,
                      slidesToScroll: 1,
                      vertical: false,
                    }
                  },
                  {
                    breakpoint: 400,
                    settings: {
                      slidesToShow: 2,
                      slidesToScroll: 1,
                      vertical: false,
                    }
                  }
                ],
          });
        
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

        //***************************
        // slider Functions
        //***************************
          $( function() {
              $( "#slider-range" ).slider({
                range: true,
                min: 0,
                max: 100,
                values: [ 0, 58 ],
                slide: function( event, ui ) {
                  $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                }
              });
              $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
                " - $" + $( "#slider-range" ).slider( "values", 1 ) );
            } );

          //***************************
        // slider Functions
        //***************************
          $( function() {
              $( "#slider-range2" ).slider({
                range: true,
                min: 1200,
                max: 5500,
                values: [ 1200, 4000 ],
                slide: function( event, ui ) {
                  $( "#amount2" ).val( "" + ui.values[ 0 ] + "CC " + ui.values[ 1 ] + "CC " );
                }
              });
              $( "#amount2" ).val( "" + $( "#slider-range2" ).slider( "values", 0 ) +
                "CC " + $( "#slider-range2" ).slider( "values", 1 ) + "CC" );
            } );


        //***************************
        // automobile-listing-slide Functions
        //***************************
          jQuery('.automobile-listing-slide').slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 2000,
              infinite: true,
              dots: true,
              arrows: false,
              fade: true,
              responsive: [
                    {
                      breakpoint: 1024,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                      }
                    },
                    {
                      breakpoint: 800,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 400,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
                  ]
            });
        //***************************
        // automobile-listing-slide2 Functions
        //***************************
          jQuery('.automobile-listing-slide2').slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              autoplay: true,
              autoplaySpeed: 2000,
              infinite: true,
              dots: false,
              prevArrow: "<span class='slick-arrow-left'><i class='icon-right-arrow'></i></span>",
              nextArrow: "<span class='slick-arrow-right'><i class='icon-arrows23'></i></span>",
              fade: true,
              responsive: [
                    {
                      breakpoint: 1024,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                      }
                    },
                    {
                      breakpoint: 800,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    },
                    {
                      breakpoint: 400,
                      settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                      }
                    }
                  ]
            });

        //***************************
    // ShopSlider Functions
    //***************************
      jQuery('.automobile-listing-grid-slider').slick({
          slidesToShow: 3,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          infinite: true,
          dots: false,
          prevArrow: "<span class='slick-arrow-left'><i class='icon-arrows23'></i></span>",
          nextArrow: "<span class='slick-arrow-right'><i class='icon-right-arrow'></i></span>",
          responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
                {
                  breakpoint: 800,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 400,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
              ]
        });

    //***************************
    // ShopSlider Functions
    //***************************
      jQuery('.automobile-testimonial-wrap').slick({
          slidesToShow: 2,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          infinite: true,
          dots: false,
          prevArrow: "<span class='slick-arrow-left'><i class='icon-arrows23'></i></span>",
          nextArrow: "<span class='slick-arrow-right'><i class='icon-right-arrow'></i></span>",
          responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
                {
                  breakpoint: 800,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 400,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
              ]
        });
    //***************************
    // ShopSlider Functions
    //***************************
      jQuery('.automobile-car-review-slider').slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 2000,
          infinite: true,
          dots: false,
          prevArrow: "<span class='slick-arrow-left'><i class='icon-arrows23'></i></span>",
          nextArrow: "<span class='slick-arrow-right'><i class='icon-right-arrow'></i></span>",
          responsive: [
                {
                  breakpoint: 1024,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                  }
                },
                {
                  breakpoint: 800,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 400,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
              ]
        });

      //***************************
      // ThumbSlider Functions
      //***************************
      jQuery('.automobile-images-thumb').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: "<span class='slick-arrow-left'><i class='icon-arrows23'></i></span>",
            nextArrow: "<span class='slick-arrow-right'><i class='icon-right-arrow'></i></span>",
            fade: true,
            asNavFor: '.automobile-images-list'
          });
          jQuery('.automobile-images-list').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.automobile-images-thumb',
            dots: false,
            vertical: false,
            prevArrow: "<span class='slick-arrow-left'><i class='icon-arrows23'></i>Previous Vehicle</span>",
            nextArrow: "<span class='slick-arrow-right'>Next Vehicle<i class='icon-right-arrow'></i></span>",
            centerMode: false,
            focusOnSelect: true,
            responsive: [
                  {
                    breakpoint: 1024,
                    settings: {
                      slidesToShow: 5,
                      slidesToScroll: 1,
                      infinite: true,
                      vertical: false,
                    }
                  },
                  {
                    breakpoint: 800,
                    settings: {
                      slidesToShow: 3,
                      slidesToScroll: 1,
                      vertical: false,
                    }
                  },
                  {
                    breakpoint: 400,
                    settings: {
                      slidesToShow: 2,
                      slidesToScroll: 1,
                      vertical: false,
                    }
                  }
                ],
          });

        $(".alert").alert()

        //***************************
        // input calander Function
        //***************************
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0);
         
        var checkin = $('#dp1,#dp2,#dp3').datepicker({
          onRender: function(date) {
            return date.valueOf() < now.valueOf() ? '' : '';
          }
        })
    
        //***************************
        // niceScrol Function
        //***************************
        $(".boxscroll").niceScroll({
          cursorborder:"none",
          cursorcolor:"#d0d0d0",
          cursorwidth: "9px",
          background: "rgba(243,243,243,1.0)",
          width: "9px",
          cursorborderradius: "5px 5px 0px 0px",
          cursoropacitymin: "1",
          boxzoom:false
        });
    

});

        // When the window has finished loading create our google map below
            google.maps.event.addDomListener(window, 'load', init);
        
            function init() {
                // Basic options for a simple Google Map
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                var mapOptions = {
                    // How zoomed in you want the map to start at (always required)
                    zoom: 11,

                    // The latitude and longitude to center the map (always required)
                    center: new google.maps.LatLng(40.6700, -73.9400), // New York

                    // How you would like to style the map. 
                    // This is where you would paste any style found on Snazzy Maps.
                    styles: [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"hue":"#ff0000"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"landscape.natural.terrain","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway.controlled_access","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.line","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.station.bus","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"transit.station.rail","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#f66a00"},{"visibility":"on"}]}]
                };

                // Get the HTML DOM element that will contain your map 
                // We are using a div with id="map" seen below in the <body>
                var mapElement = document.getElementById('map');

                // Create the Google Map using our element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                // Let's also add a marker while we're at it
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(40.6700, -73.9400),
                    map: map,
                    title: 'Snazzy!'
                });
  }

  //***************************
    // TOUR GRID Function
    //***************************
    jQuery(window).on('load', function() {
    var $grid = $('.automobile-car-deals-filter').isotope({
      itemSelector: '.element-item',
      layoutMode: 'fitRows'
    });
    // filter functions
    var filterFns = {
      // show if number is greater than 50
      numberGreaterThan50: function() {
        var number = $(this).find('.number').text();
        return parseInt( number, 10 ) > 50;
      },
      // show if name ends with -ium
      ium: function() {
        var name = $(this).find('.name').text();
        return name.match( /ium$/ );
      }
    };
    // bind filter button click
    $('.filters-button-group').on( 'click', 'a', function() {
      var filterValue = $( this ).attr('data-filter');
      // use filterFn if matches value
      filterValue = filterFns[ filterValue ] || filterValue;
      $grid.isotope({ filter: filterValue });
    });
    // change is-checked class on buttons
    $('.filters-button-group').each( function( i, buttonGroup ) {
      var $buttonGroup = $( buttonGroup );
      $buttonGroup.on( 'click', 'a', function() {
        $buttonGroup.find('.is-checked').removeClass('is-checked');
        $( this ).addClass('is-checked');
      });
    }); 

});

//********************************
    // Mediaelementplayer Function
    //********************************
    jQuery('video').mediaelementplayer({
    success: function(player, node) {
      jQuery('#' + node.id + '-mode').html('mode: ' + player.pluginType);
    }
  });