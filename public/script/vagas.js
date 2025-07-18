document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-vaga');
  const lista = document.getElementById('lista-vagas');

  let vagasCache = [];

  async function buscarVagas() {
    try {
      const response = await fetch('getVagas');
      const data = await response.json();
      vagasCache = data.vagas;
      atualizarLista(data.vagas);
    } catch (error) {
      lista.innerHTML = '<tr><td colspan="6">Erro ao buscar vagas</td></tr>';
    }
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const formData = new FormData(form);

    try {
      const response = await fetch('cadastrarVaga', {
        method: 'POST',
        body: formData,
      });
      if (response.ok) {
        alert('Vaga cadastrada com sucesso!');
        form.reset();
        form.classList.remove('was-validated');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalCriarVaga'));
        if (modal) modal.hide();
        buscarVagas();
      } else {
        alert('Erro ao cadastrar vaga.');
      }
    } catch (error) {
      alert('Erro de conexão.');
    }
  });

  function abrirModalEdicao(index) {
    const vaga = vagasCache[index];
    if (!vaga) return;
    // Preencher o formulário do modal de edição
    document.getElementById('edit-id').value = vaga.Id_Vaga;
    document.getElementById('edit-titulo').value = vaga.Nome;
    document.getElementById('edit-area').value = vaga.Area;
    document.getElementById('edit-local').value = vaga.Localizacao;
    document.getElementById('edit-contrato').value = vaga.Tipo_de_Contrato;
    document.getElementById('edit-nivel').value = vaga.Nivel_da_Vaga;
    document.getElementById('edit-salario').value = vaga.Salario;
    document.getElementById('edit-beneficios').value = vaga.Beneficios;
    document.getElementById('edit-descricao').value = vaga.Descricao;
    document.getElementById('edit-requisitos').value = vaga.Requisitos_Obrigatorios;
    document.getElementById('edit-diferenciais').value = vaga.Diferenciais;
    const modal = new bootstrap.Modal(document.getElementById('modalEditarVaga'));
    modal.show();
  }

  document.getElementById('form-editar-vaga').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    try {
      const response = await fetch('editarVaga', {
        method: 'POST',
        body: formData,
      });
      const data = await response.json();
      if (data.success) {
        alert('Vaga editada com sucesso!');
        buscarVagas();
        bootstrap.Modal.getInstance(document.getElementById('modalEditarVaga')).hide();
      } else {
        alert('Erro ao editar vaga.');
      }
    } catch (error) {
      alert('Erro de conexão.');
    }
  });

  function excluirVaga(index) {
    const vaga = vagasCache[index];
    if (!vaga) return;
    if (!confirm('Tem certeza que deseja excluir esta vaga?')) return;
    fetch('excluirVaga', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + encodeURIComponent(vaga.Id_Vaga)
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Vaga excluída com sucesso!');
          buscarVagas();
        } else {
          alert('Erro ao excluir vaga.');
        }
      })
      .catch(() => alert('Erro de conexão.'));
  }

  function atualizarLista(vagas = []) {
    lista.innerHTML = '';
    if (vagas.length === 0) {
      lista.innerHTML = '<tr><td colspan="6">Nenhuma vaga encontrada</td></tr>';
      return;
    }
    vagas.forEach((vaga, index) => {
      lista.innerHTML += `
        <tr>
          <td>${vaga.Nome}</td>
          <td>${vaga.Area}</td>
          <td>${vaga.Nivel_da_Vaga}</td>
          <td>${vaga.Localizacao}</td>
          <td>${vaga.Tipo_de_Contrato}</td>
          <td>
            <a href="#" class="text-primary me-2" onclick="window.abrirModalEdicao(${index})">Editar</a>
            <a href="#" class="text-danger" onclick="window.excluirVaga(${index})">Excluir</a>
          </td>
        </tr>
      `;
    });
  }

  window.abrirModalEdicao = abrirModalEdicao;
  window.excluirVaga = excluirVaga;

  buscarVagas();
});
