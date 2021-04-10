document.addEventListener('DOMContentLoaded', function () {

  let userDeleteButton = document.querySelector('#btn-user-delete');

  userDeleteButton.addEventListener('click', () => {
    let modal = document.querySelector('#modal-delete');
    modal.classList.add('open');
    let exits = modal.querySelectorAll('.modal-exit');
    exits.forEach(function(exit) {
      exit.addEventListener('click', function(event) {
        event.preventDefault();
        modal.classList.remove('open');
      });
    });
  })

  let confirmUserDeleteBtn = document.querySelector('#btn-confirm-user-delete');

  confirmUserDeleteBtn.addEventListener('click', () => {

    let userId = document.querySelector('#user_id').value;
    
    
  });

});