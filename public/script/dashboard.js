// Busca os dados do backend
fetch('dashboard/candidatos-mes')
  .then(res => res.json())
  .then(dados => {
    const ctx = document.getElementById('meuGrafico').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: dados.labels,
        datasets: [{
          label: dados.label,
          data: dados.valores,
      backgroundColor: '#2f5fba'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        },
        scales: {
          y: {
            beginAtZero: true,
            precision: 0
          }
        }
      }
    });
  })
  .catch(err => {
    console.error('Erro ao buscar dados do gráfico:', err);
  });