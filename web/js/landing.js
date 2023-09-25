let openModalLinks = document.getElementsByClassName("open-modal");
let closeModalLinks = document.getElementsByClassName("form-modal-close");
let overlay = document.getElementsByClassName("overlay")[0];

for (let i = 0; i < openModalLinks.length; i++) {
  let modalLink = openModalLinks[i];

  modalLink.addEventListener("click", function (event) {
    let modalId = event.currentTarget.getAttribute("data-for");

    let modal = document.getElementById(modalId);
    modal.setAttribute("style", "display: block");
    overlay.setAttribute("style", "display: block");

  });
}

let closeModal = (event) => {
  let modal = event.currentTarget.parentElement;

  modal.removeAttribute("style");
  overlay.removeAttribute("style");
}

for (let j = 0; j < closeModalLinks.length; j++) {
  let closeModalLink = closeModalLinks[j];

  closeModalLink.addEventListener("click", closeModal)
}