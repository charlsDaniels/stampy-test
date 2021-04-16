document.addEventListener('DOMContentLoaded', function () {

  //selecciono el botón de cambiar contraseña.
  let userPassChangeBtn = document.getElementById('btn-user-pass-change');

  //les agrego los eventos necesarios para su correcto funcionamiento.
  userPassChangeBtn.addEventListener('click', () => {

    //busco el modal de cambiar contraseña.
    let modal = document.getElementById('modal-pass');

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

    let userId = userPassChangeBtn.dataset.user_id;

    //busco el form que es necesario enviar para eliminar el usuario.
    let confirmPassChangeBtn = document.getElementById('btn-confirm-pass-change');

    let form = confirmPassChangeBtn.parentNode;
      
    //agrego un input hidden con el id del usuario a eliminar como value.
    form.innerHTML += `<input type="hidden" name="user_id" value=${userId}>`
    form.innerHTML = form.innerHTML

    //hago el submit del formulario para eliminar el usuario.
    confirmPassChangeBtn.addEventListener('click', function(event) {
      event.preventDefault();      
      form.submit()
    })      
  });
});