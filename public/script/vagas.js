document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-vaga');
  const lista = document.getElementById('lista-vagas');

  const vagas = [];

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const novaVaga = {
      titulo: form.titulo.value,
      area: form.area.value,
      local: form.local.value,
      contrato: form.contrato.value,
      nivel: form.nivel.value,
      descricao: form.descricao.value,
      status: 'Aberta',
    };

    vagas.push(novaVaga);
    atualizarLista();
    form.reset();
    form.classList.remove('was-validated');
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCriarVaga'));
    modal.hide();
  });

  function atualizarLista() {
    lista.innerHTML = '';
    vagas.forEach((vaga, index) => {
      lista.innerHTML += `
        <tr>
          <td>${vaga.titulo}</td>
          <td>${vaga.area}</td>
          <td>${vaga.nivel}</td>
          <td>${vaga.local}</td>
          <td>${vaga.status}</td>
          <td>
            <a href="#" class="text-primary me-2" onclick="editarVaga(${index})">Editar</a>
            <a href="#" class="text-danger" onclick="excluirVaga(${index})">Excluir</a>
          </td>
        </tr>
      `;
    });
  }

  window.editarVaga = function(index) {
    alert('Editar vaga ainda não implementado.');
  };

  window.excluirVaga = function(index) {
    vagas.splice(index, 1);
    atualizarLista();
  };
});
