document.addEventListener("DOMContentLoaded", () => {
  "use strict";

  // Your code here

  // SOLVE THE PROBLEM OF WP ADMIN BAR OVERLAPPING THE NAVBAR
  const styleElement = document.createElement("style");
  styleElement.innerHTML = `
    @media only screen and (max-width:600px) {
      body.admin-bar #wpadminbar.nojq {
        top: -46px !important;
      }
    }
  `;
  document.head.appendChild(styleElement);
});
