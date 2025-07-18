document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.needs-validation');

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    event.stopPropagation();

    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch('cadastrarCurriculo', {
        method: 'POST',
        body: formData,
      });

      if (response.ok) {
        alert('Currículo enviado com sucesso!');
        form.reset();
        form.classList.remove('was-validated');
        const modal = bootstrap.Modal.getInstance(document.getElementById('formModal'));
        if (modal) modal.hide();
      } else {
        alert('Erro ao enviar. Tente novamente.');
      }
    } catch (error) {
      console.error('Erro:', error);
      alert('Falha na conexão com o servidor.');
    }
  });
});
