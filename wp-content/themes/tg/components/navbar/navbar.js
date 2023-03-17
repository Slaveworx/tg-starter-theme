jQuery(document).ready(($) => {
  //*********************************/
  // navbar
  // @version 1.0
  // @package tg
  //*********************************/

  //Open and Close Navbar
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

  // ON SCROLL EFFECTS
  let vh = $(window).height();
  let initialFold = 100;
  let prevScrollPos = 0;

  $(window).on("scroll", function () {
    let currentScrollPos = $(window).scrollTop();

    if (currentScrollPos > initialFold) {
      navbar.addClass("fixed");
      if (currentScrollPos > prevScrollPos) {
        navbar.css("opacity", 1);
      } else {
        let opacity = Math.max(0, 1 - (currentScrollPos - initialFold) / vh);
        navbar.css("opacity", opacity);
      }
    } else {
      navbar.removeClass("fixed").css("opacity", 1);
    }

    prevScrollPos = currentScrollPos;
  });
  navbar.css("transition", "opacity 0.2s ease-in-out");

  // Fix Wp Admin Bar issue
  if ($("body").hasClass("logged-in")) {
    $("html").attr("style", "margin: 0 !important");

    if ($(window).width() < 782) {
      $("body").attr("style", "margin-top: 32px !important");
    } else {
      $("body").attr("style", "margin-top: 46px !important");
    }
  }
});
