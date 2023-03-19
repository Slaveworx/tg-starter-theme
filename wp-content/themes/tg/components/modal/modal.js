jQuery(document).ready(($) => {
  //*********************************/
  // modal
  // @version 1.0
  // @package tg
  //*********************************/

  let modal = $(".modal");

  // Open the modal
  $("#open-modal").click(function (el) {
    el.preventDefault();
    $(document.body).addClass("scroll-blocked");
    modal.fadeIn();
  });

  // Close the modal
  $(".modal-close").click(function () {
    $(document.body).removeClass("scroll-blocked");
    modal.fadeOut();
  });
});
