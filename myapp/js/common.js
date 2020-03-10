$(function() {
    $('ul.tabs__caption').on('click', 'li:not(.active)', function() {
        $(this)
          .addClass('active').siblings().removeClass('active')
          .closest('div.tabs').find('div.tabs__content').removeClass('active').eq($(this).index()).addClass('active');
      });

      $('.institutions__slider').owlCarousel({
        loop: false,
        dots: false,
        margin: 40,
        nav: false,
        // autoWidth:true,
        // navText: ['<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"><metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata><g><g transform="matrix(1 0 0 -1 0 1008)"><path d="M756.2,741.8L990,508L756.2,274.2l-27,27L918.1,490H10v36h908.1L729.3,714.8L756.2,741.8z"/></g></g></svg>', '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"><metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata><g><g transform="matrix(1 0 0 -1 0 1008)"><path d="M756.2,741.8L990,508L756.2,274.2l-27,27L918.1,490H10v36h908.1L729.3,714.8L756.2,741.8z"/></g></g></svg>'],
        responsive: {
            0: {
                items: 2
            },
            768: {
                items: 2
            },
            1000: {
                items: 2
            }
        }
    });
    //select-number form
    if(jQuery('.phone-mask').length) {
        jQuery(function($){
            $(".phone-mask").mask("+38(999) 999-9999");
        });
    }
    if(jQuery('.modal__wrap').length) {
        let modalWrap = $('.modal__wrap');
        
        //popup
        $(".modal-open").click(function (e){
          e.preventDefault();
          var numModal = $(this).attr('href');
          var modal =  $(numModal);
          modalWrap.removeClass('fadeOutUp');
          modalWrap.addClass('fadeInDown');
          modal.removeClass('disabled');
          modal.addClass('flex');
          $('body').addClass('body-modal-open');
          // body.addClass('body-modal');
        });
      
        // $(".modal-open").bind('touchstart', function(e){
        //   e.preventDefault();
        //   var numModal = $(this).attr('href');
        //   var modal =  $(numModal);
        //   modalWrap.removeClass('fadeOutUp');
        //   modalWrap.addClass('fadeInDown');
        //   modal.removeClass('disabled');
        //   modal.addClass('flex');
        //   // body.addClass('body-modal');
        // });
      
      
        $('.modal-close').click(function (){
      
          modalWrap.removeClass('fadeInDown');
          modalWrap.addClass('fadeOutUp');
          setTimeout(function() {
              $('.modal').addClass('disabled');
            }, 700);
          setTimeout(function() {
              $('.modal').removeClass('flex');
              $('body').removeClass('body-modal-open');
            }, 800);  
      
        });
      
        $('.modal').mouseup(function (e){ // событие клика по веб-документу
          var div = $(".modal__body"); // тут указываем ID элемента
          var close = $('.modal-close');
          if (close.is(e.target)) {
      
          } else if (!div.is(e.target) // если клик был не по нашему блоку
          && div.has(e.target).length === 0) { // и не по его дочерним элементам
              var modalWrap = $('.modal__wrap');
              modalWrap.removeClass('fadeInDown');
              modalWrap.addClass('fadeOutUp');
              setTimeout(function() {
                  $('.modal').addClass('disabled');
              }, 700);
              setTimeout(function() {
                  $('.modal').removeClass('flex');
                  $('body').removeClass('body-modal-open');
              }, 800); 
            
          }
        });
      }
    //popup
    // if(jQuery('.modal__wrap').length) {
    //     let modalWrap = $('.modal__wrap');
    //     //popup
    //     $(".modal-open").click(function (e){
    //       e.preventDefault();
    //       var btn = $(this);

    //             var numModal = btn.attr('href');
    //             var modal =  $(numModal);
    //             modalWrap.removeClass('fadeOutUp');
    //             modalWrap.addClass('fadeInDown');
    //             modal.removeClass('disabled');
    //             modal.addClass('flex');
    //             $('body').addClass('body-modal-open');


    //     });
    //     // $(".modal-open").click(function (e){
    //     //   e.preventDefault();
    //     //   var btn = $(this);
    //     //     $($(this).parent().parent()).each(function () {
    //     //         var form = $(this);
    //     //         form.find('.rfield').addClass('empty_field');

    //     //            // Функция проверки полей формы

    //     //             form.find('.rfield').each(function(){
    //     //             if($(this).val() != ''){
    //     //                 // Если поле не пустое удаляем класс-указание
    //     //             $(this).removeClass('empty_field');

    //     //             if (!form.find('.empty_field').length) {
    //     //                 var numModal = btn.attr('href');
    //     //                 var modal =  $(numModal);
    //     //                 modalWrap.removeClass('fadeOutUp');
    //     //                 modalWrap.addClass('fadeInDown');
    //     //                 modal.removeClass('disabled');
    //     //                 modal.addClass('flex');
    //     //                 $('body').addClass('body-modal-open');
    //     //                 // body.addClass('body-modal');
    //     //                 }
    //     //             } else {
    //     //                 // Если поле пустое добавляем класс-указание
    //     //             $(this).addClass('empty_field');
    //     //             }
    //     //         });
    //     //     })
    //     // });
    //     $('.modal-close').click(function (){
    //       modalWrap.removeClass('fadeInDown');
    //       modalWrap.addClass('fadeOutUp');
    //       setTimeout(function() {
    //           $('.modal').addClass('disabled');
    //         }, 700);
    //       setTimeout(function() {
    //           $('.modal').removeClass('flex');
    //           $('body').removeClass('body-modal-open');
    //         }, 800);
    //     });
    //     $('.modal').mouseup(function (e){ // событие клика по веб-документу
    //       var div = $(".modal__body"); // тут указываем ID элемента
    //       var close = $('.modal-close');
    //       if (close.is(e.target)) {
    //       } else if (!div.is(e.target) // если клик был не по нашему блоку
    //       && div.has(e.target).length === 0) { // и не по его дочерним элементам
    //           var modalWrap = $('.modal__wrap');
    //           modalWrap.removeClass('fadeInDown');
    //           modalWrap.addClass('fadeOutUp');
    //           setTimeout(function() {
    //               $('.modal').addClass('disabled');
    //           }, 700);
    //           setTimeout(function() {
    //               $('.modal').removeClass('flex');
    //               $('body').removeClass('body-modal-open');
    //           }, 800);
    //       }
    //     });
    // }
    //scrollto
    
    if(jQuery('.scroll-to').length) {
        var $page = $('html, body');
        $('.scroll-to[href*="#"]').click(function() {
            $page.animate({
                scrollTop: $($.attr(this, 'href')).offset().top
            }, 400);
            return false;
        });
    }

});



document.addEventListener('DOMContentLoaded', function(){
//menu
    var menu = document.querySelector('.menu-toggle');
    menu.addEventListener('click', function(){
        var nav = document.querySelector('.main-menu');
        nav.classList.toggle('active');
        var navGamb = document.querySelector('.menu-toggle');
        navGamb.classList.toggle('active');
    });
//tabs
	// store tabs variable
	var myTabs = document.querySelectorAll("ul.header__tabs > li");
    function myTabClicks(tabClickEvent) {
		for (var i = 0; i < myTabs.length; i++) {
			myTabs[i].classList.remove("active");
		}
		var clickedTab = tabClickEvent.currentTarget;
		clickedTab.classList.add("active");
		tabClickEvent.preventDefault();
		var myContentPanes = document.querySelectorAll(".tab-pane");
		for (i = 0; i < myContentPanes.length; i++) {
			myContentPanes[i].classList.remove("active");
		}
        var anchorReference = tabClickEvent.target;
        console.log(anchorReference);
        var activePaneId = anchorReference.getAttribute("href");
        console.log(activePaneId);
        var activePane = document.querySelector(activePaneId);
        console.log(activePaneId);
		activePane.classList.add("active");
    }
    for (i = 0; i < myTabs.length; i++) {
		myTabs[i].addEventListener("click", myTabClicks)
	}





});
