jQuery(document).ready(($) => {
  //*********************************/
  // navbar
  // @version 1.0
  // @package tg
  //*********************************/

  const headerNav = $("#site-navigation");
  const navBurger = $(".theme-burger-wrapper");
  const navbar = $(".site-header");
  const body = $(document.body);

  navBurger.click(() => {
    const visibility = headerNav.attr("data-visible");

    if (visibility === "false") {
      //when clicked to open
      body.toggleClass("scroll-blocked");
      headerNav.attr("data-visible", "true");
      navBurger.toggleClass("close");
      navbar.addClass("menu-open");
    } else {
      body.toggleClass("scroll-blocked");
      headerNav.attr("data-visible", "false");
      navBurger.toggleClass("close");
      navbar.removeClass("menu-open");
    }
  });
});
