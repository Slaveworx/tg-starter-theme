document.addEventListener("DOMContentLoaded", () => {
  //*********************************/
  // navbar
  // @version 1.0
  // @package tg
  //*********************************/

  // Open and Close Navbar
  const headerNav = document.getElementById("site-navigation");
  const navBurger = document.querySelector(".theme-burger-wrapper");
  const navbar = document.querySelector(".site-header");
  const body = document.body;

  navBurger.addEventListener("click", () => {
    const visibility = headerNav.getAttribute("data-visible");

    if (visibility === "false") {
      //when clicked to open
      body.classList.toggle("scroll-blocked");
      headerNav.setAttribute("data-visible", "true");
      navBurger.classList.toggle("close");
      navbar.classList.add("menu-open");
    } else {
      body.classList.toggle("scroll-blocked");
      headerNav.setAttribute("data-visible", "false");
      navBurger.classList.toggle("close");
      navbar.classList.remove("menu-open");
    }
  });

  // ON SCROLL EFFECTS
  let vh = window.innerHeight;
  let initialFold = 800;
  let prevScrollPos = 0;

  const bodyHeight = document.body.offsetHeight;

  if (bodyHeight > vh) {
    window.addEventListener("scroll", () => {
      let currentScrollPos = window.pageYOffset;

      if (currentScrollPos > initialFold) {
        navbar.classList.add("fixed");
        if (currentScrollPos > prevScrollPos) {
          navbar.style.opacity = 1;
        } else {
          let opacity = Math.max(0, 1 - (currentScrollPos - initialFold) / vh);
          navbar.style.opacity = opacity;
        }
      } else {
        navbar.classList.remove("fixed");
        navbar.style.opacity = 1;
      }

      prevScrollPos = currentScrollPos;
    });
    navbar.style.transition = "opacity 350ms ease-in-out";
  }

  // Fix Wp Admin Bar issue
  if (body.classList.contains("logged-in")) {
    document.documentElement.style.margin = "0 !important";

    if (window.innerWidth < 782) {
      body.style.marginTop = "32px !important";
    } else {
      body.style.marginTop = "46px !important";
    }
  }
});
