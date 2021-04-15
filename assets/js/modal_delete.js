document.addEventListener('DOMContentLoaded', function () {

  //selecciono todos los botones de eliminar usuario de la tabla.
  let usersDeleteButtons = document.getElementsByName('btn-user-delete');

  //les agrego los eventos necesarios para su correcto funcionamiento.
  usersDeleteButtons.forEach(function(trigger) {
    trigger.addEventListener('click', () => {

      //busco el modal de eliminar usuario.
      let modal = document.querySelector('#modal-delete');

      //le agrego la clase  para que al ser clickeado se abra.
      modal.classList.add('open');

      //y también el evento para poder cerrarlo...
      let exits = modal.querySelectorAll('.modal-exit');
      exits.forEach(function(exit) {
        exit.addEventListener('click', (event) => {
          event.preventDefault();
          modal.classList.remove('open');
        });
      });

      let userId = trigger.dataset.user_id;

      //busco el form que es necesario enviar para eliminar el usuario.
      let confirmUserDeleteBtn = document.getElementsByName('btn-confirm-user-delete');
      let form = confirmUserDeleteBtn[0].parentNode;

      //remuevo el input hidden anterior si es que hay
      inputPrevio = form.children[1]
      if (inputPrevio) {
        form.removeChild(inputPrevio)
      }
  
      //agrego un input hidden con el id del usuario a eliminar como value.
      form.innerHTML += `<input type="hidden" name="user_id" value=${userId}>`
      form.innerHTML = form.innerHTML

      //hago el submit del formulario para eliminar el usuario.
      confirmUserDeleteBtn[0].addEventListener('click', function(event) {
        event.preventDefault();
        form.submit()
      })      
    })
  });
});