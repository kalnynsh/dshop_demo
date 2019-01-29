$(document).ready(function () {
    var slideShow = new Swiper('#slideshow0', {
          mode: 'horizontal',
          slidesPerView: 1,
          pagination: '.slideshow0',
          paginationClickable: true,
          nextButton: '.swiper-button-next',
          prevButton: '.swiper-button-prev',
          spaceBetween: 30,
          autoplay: 2500,
          autoplayDisableOnInteraction: true,
          loop: true
    });
    
    var carousel = new Swiper('#carousel0', {
          mode: 'horizontal',
          slidesPerView: 5,
          pagination: '.carousel0',
          paginationClickable: true,
          nextButton: '.swiper-button-next',
          prevButton: '.swiper-button-prev',
          autoplay: 2500,
          loop: true
    });
});

