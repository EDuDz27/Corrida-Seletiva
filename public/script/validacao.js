document.addEventListener('DOMContentLoaded', () => {
  'use strict';

  const forms = document.querySelectorAll('.needs-validation');

  forms.forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      } else {
        event.preventDefault(); // impede o envio real para testar
        alert("Formulário válido! Seus dados foram enviados com sucesso!");
        // Aqui depois você pode colocar o envio para um servidor ou API
      }

      form.classList.add('was-validated');
    });
  });
});
