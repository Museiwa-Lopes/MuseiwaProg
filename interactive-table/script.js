const data = [
  { nome: "João Silva", email: "joao@email.com", pais: "Moçambique" },
  { nome: "Ana Costa", email: "ana@email.com", pais: "Portugal" },
  { nome: "Carlos Mendes", email: "carlos@email.com", pais: "Brasil" },
  { nome: "Fatima Sousa", email: "fatima@email.com", pais: "Angola" },
  { nome: "Luís Fernando", email: "luis@email.com", pais: "Cabo Verde" },
  { nome: "Eva Ramos", email: "eva@email.com", pais: "Guiné-Bissau" },
];

const tbody = document.querySelector("#dataTable tbody");
const filterInput = document.getElementById("filterInput");
const filterInfo = document.getElementById("filterInfo");

// Renderizar a tabela
function renderTable(filteredData) {
  tbody.innerHTML = "";
  filteredData.forEach(item => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${item.nome}</td>
      <td>${item.email}</td>
      <td>${item.pais}</td>
    `;
    tbody.appendChild(row);
  });
}

// Filtrar os dados
function filterTable(keyword) {
  const lowerKeyword = keyword.toLowerCase();
  const filtered = data.filter(item =>
    Object.values(item).some(value =>
      value.toLowerCase().includes(lowerKeyword)
    )
  );

  renderTable(filtered);

  if (keyword) {
    filterInfo.textContent = `A filtrar por: "${keyword}" em todas as colunas.`;
  } else {
    filterInfo.textContent = "Nenhum filtro activo.";
  }
}

// Event Listener
filterInput.addEventListener("input", () => {
  filterTable(filterInput.value);
});

// Renderizar dados iniciais
renderTable(data);
