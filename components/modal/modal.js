document.addEventListener("DOMContentLoaded", () => {
  //*********************************/
  // modal
  // @version 1.0
  // @package tg
  //*********************************/

  const modal = document.querySelector(".modal");

  // Open the modal
  document.querySelector("#open-modal").addEventListener("click", (event) => {
    event.preventDefault();
    document.body.classList.add("scroll-blocked");
    modal.classList.add("visible");
  });

  // Close the modal
  document.querySelector(".modal-close").addEventListener("click", () => {
    document.body.classList.remove("scroll-blocked");
    modal.classList.remove("visible");
  });
});