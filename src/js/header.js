// Variables
let $ = jQuery;
// Functions
const isMobile = (function (a) {
   return /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
       a
     ) ||
     /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw(n|u)|c55\/|capi|ccwa|cdm-|cell|chtm|cldc|cmd-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc-s|devi|dica|dmob|do(c|p)o|ds(12|d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(-|_)|g1 u|g560|gene|gf-5|g-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd-(m|p|t)|hei-|hi(pt|ta)|hp( i|ip)|hs-c|ht(c(-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i-(20|go|ma)|i230|iac( |-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|-[a-w])|libw|lynx|m1-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|-([1-8]|c))|phil|pire|pl(ay|uc)|pn-2|po(ck|rt|se)|prox|psio|pt-g|qa-a|qc(07|12|21|32|60|-[2-7]|i-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h-|oo|p-)|sdk\/|se(c(-|0|1)|47|mc|nd|ri)|sgh|shar|sie(|m)|sk0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h|v|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl|tdg|tel(i|m)|tim|tmo|to(pl|sh)|ts(70|m|m3|m5)|tx9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas|your|zeto|zte/i.test(
       a.substr(0, 4)
     );
})(navigator.userAgent || navigator.vendor || window.opera);
const isValidZip = zipStr => !isNaN(zipStr) && zipStr.length === 5;
$(() => {
   $('header #primary-menu .menu-item').on({
      mouseenter: e => {
         $(e.currentTarget).find('> a').addClass('active');
         const $submenu = $(e.currentTarget).find('.submenu-container');
         if($submenu.length) {
            // $submenu.show();
            $submenu.css('display', 'flex');
            // $(e.currentTarget).find('.material-icons').addClass('rotate-180');
            // $(e.currentTarget).children('.ripple-el').children('.material-icons').addClass('rotate-180');
         }
      },
      mouseleave: e => {
         $('.menu-item').find('> a').removeClass('active');
         // $(e.currentTarget).find('.material-icons').removeClass('rotate-180');
         // $(e.currentTarget).children('.ripple-el').children('.material-icons').removeClass('rotate-180');
         $('.submenu-container').hide();
      }
   });

   $('.overlay #primary-menu-sidebar .menu-item').on('click', e => {
      const $submenu = $(`.submenu[data-parent-menu-title="${$(e.currentTarget).attr('data-parent-menu-title')}"]`);
      
      if( $(e.currentTarget).hasClass('active')){
         $(e.currentTarget).children('.submenu-container').slideToggle(500);
         $(e.currentTarget).removeClass('active');
      } else{
         // remove all active classes
         $('.overlay #primary-menu-sidebar .menu-item').each(function(){
            if( $(this).hasClass('active') ){
               $(this).children('.submenu-container').slideToggle(500);
            }
            $(this).removeClass('active');
         })
         // add active class to clicked li
         $(e.currentTarget).addClass('active');
         $(e.currentTarget).children('.submenu-container').slideToggle(500);
      }
      

      if($submenu.length) {
         $submenu.toggleClass('active');
      }
   });

   $('.submenu-item-back-link').on('click', e => {
      $(e.currentTarget).parent('.submenu.active').removeClass('active');
   });

   $('#open-header-zip-search').on('click', e => {
      $('.hide-on-tablet-search').hide();
      $('.show-on-tablet-search').show();
      $('.header-zip-search-container').addClass('tablet-view');
   });

   $('#cancel-header-zip-search').on('click', e => {
      $('.show-on-tablet-search').hide();
      $('.hide-on-tablet-search').show();
      $('.header-zip-search-container').removeClass('tablet-view');
   });

   $('#toggle-hamburger').on('click', e => {
      $(e.currentTarget).find('#navbar-hamburger').toggle();
      $(e.currentTarget).find('#navbar-close').toggle();
      $('body').toggleClass('mobile-menu-active');
      $('.overlay').toggle(0, () => {
         $('#primary-menu-sidebar').toggleClass('active');
      });
      $('.submenu').removeClass('active');
   });
   
   $('.overlay').on('click', e => {
      if(e.target === e.currentTarget) {  
         $('#toggle-hamburger').find('#navbar-hamburger').toggle();
         $('#toggle-hamburger').find('#navbar-close').toggle();
         $('body').toggleClass('mobile-menu-active');
         $('.overlay').toggle();
         $('.submenu').removeClass('active');
         $('#primary-menu-sidebar').toggleClass('active');
      }
   });

   $('.zip_search_input').not('.modal-zip-search-input').on('blur', e => {
      const validZip = isValidZip($(e.currentTarget).val());

      if(isMobile && validZip) {
         $('.zip_search_form').trigger('submit');
      }
   });

   $('.ripple-el').on('click tap', e => {
      $('.ripple').remove();

      const $circle = $('<span>');
      const $target = $(e.currentTarget);
      const diameter = Math.max($target.width(), $target.height());
      const radius = diameter / 2;
      const offset = $target.offset();

      $circle.css({
         height: `${diameter}px`,
         width: `${diameter}px`,
         left: `${e.clientX - (offset.left + radius)}px`,
         top: `${e.clientY - (offset.top + radius)}px`,
      }).addClass('ripple');

      $target.append($circle);
    });
});
