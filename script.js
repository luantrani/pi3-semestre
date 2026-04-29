const STORAGE_KEY = "prateleiras-iot-v2";
const SENSOR_STORAGE_KEY = "sensores-iot-v1";
const listaEl = document.getElementById("lista-prateleiras");
const alertasEl = document.getElementById("lista-alertas");
const formEl = document.getElementById("form-prateleira");
const templateEl = document.getElementById("template-item");
const templateAlertaEl = document.getElementById("template-alerta");
const btnSimular = document.getElementById("btn-simular");

const kpiTotal = document.getElementById("kpi-total");
const kpiNormal = document.getElementById("kpi-normal");
const kpiAtencao = document.getElementById("kpi-atencao");
const kpiVazio = document.getElementById("kpi-vazio");
const hubTotal = document.getElementById("hub-total");
const pageTitle = document.getElementById("page-title");
const menuItems = Array.from(document.querySelectorAll(".menu-item[data-view-target]"));
const views = {
  "visao-geral": document.getElementById("view-visao-geral"),
  "config-iot": document.getElementById("view-config-iot")
};
const viewOnlyElements = Array.from(document.querySelectorAll("[data-view-only]"));

const sensorFormEl = document.getElementById("form-sensor");
const sensorTableBodyEl = document.getElementById("sensor-table-body");
const sensorTemplateEl = document.getElementById("template-sensor-row");

const dadosIniciais = [
  { id: crypto.randomUUID(), corredor: "Corredor A1", nome: "Bebidas - Refrigerantes", distancia: 24, maximo: 120, atualizadoEm: new Date().toISOString() },
  { id: crypto.randomUUID(), corredor: "Corredor A1", nome: "Bebidas - Sucos", distancia: 77, maximo: 120, atualizadoEm: new Date().toISOString() },
  { id: crypto.randomUUID(), corredor: "Corredor A2", nome: "Limpeza - Sabao em Po", distancia: 108, maximo: 120, atualizadoEm: new Date().toISOString() },
  { id: crypto.randomUUID(), corredor: "Corredor A2", nome: "Limpeza - Amaciante", distancia: 117, maximo: 120, atualizadoEm: new Date().toISOString() }
];

let prateleiras = carregar();
let sensores = carregarSensores();

function carregar() {
  const bruto = localStorage.getItem(STORAGE_KEY);
  if (!bruto) return dadosIniciais;
  try {
    const parsed = JSON.parse(bruto);
    return Array.isArray(parsed) && parsed.length ? parsed : dadosIniciais;
  } catch (error) {
    return dadosIniciais;
  }
}

function salvar() {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(prateleiras));
}

function carregarSensores() {
  const bruto = localStorage.getItem(SENSOR_STORAGE_KEY);
  if (!bruto) {
    return [
      {
        id: crypto.randomUUID(),
        nome: "Sensor Bebidas 1",
        sensorId: "US-0101",
        corredor: "Corredor A1",
        lado: "A",
        categoria: "Bebidas - Refrigerantes",
        mac: "00:1B:44:11:3A:B7",
        status: "online"
      },
      {
        id: crypto.randomUUID(),
        nome: "Sensor Sucos",
        sensorId: "US-0102",
        corredor: "Corredor A1",
        lado: "B",
        categoria: "Bebidas - Sucos",
        mac: "",
        status: "online"
      }
    ];
  }

  try {
    const parsed = JSON.parse(bruto);
    if (!Array.isArray(parsed)) return [];
    return parsed.map((sensor) => ({
      ...sensor,
      lado: sensor.lado === "B" ? "B" : "A"
    }));
  } catch (error) {
    return [];
  }
}

function salvarSensores() {
  localStorage.setItem(SENSOR_STORAGE_KEY, JSON.stringify(sensores));
}

function getPercentual(item) {
  const maximo = Number(item.maximo);
  if (maximo <= 0) return 0;
  const preenchimento = ((maximo - Number(item.distancia)) / maximo) * 100;
  return Math.max(0, Math.min(100, Math.round(preenchimento)));
}

function getStatus(percentual) {
  if (percentual < 15) return "vazio";
  if (percentual <= 50) return "atencao";
  return "normal";
}

function labelStatus(status) {
  if (status === "vazio") return "Vazio";
  if (status === "atencao") return "Atencao";
  return "Normal";
}

function tempoRelativo(iso) {
  const diffMin = Math.max(0, Math.floor((Date.now() - new Date(iso).getTime()) / 60000));
  if (diffMin <= 1) return "Ha 1 min";
  return `Ha ${diffMin} min`;
}

function atualizarKpis() {
  const totais = prateleiras.reduce((acc, item) => {
    const status = getStatus(getPercentual(item));
    acc[status] += 1;
    return acc;
  }, { normal: 0, atencao: 0, vazio: 0 });

  const total = prateleiras.length;
  kpiTotal.textContent = `${total} secoes`;
  kpiNormal.textContent = String(totais.normal);
  kpiAtencao.textContent = String(totais.atencao);
  kpiVazio.textContent = String(totais.vazio);
  hubTotal.textContent = String(total);
}

function renderizarPrateleiras() {
  listaEl.innerHTML = "";
  if (!prateleiras.length) {
    listaEl.innerHTML = "<p>Nenhuma secao cadastrada.</p>";
    return;
  }

  prateleiras.forEach((item) => {
    const percentual = getPercentual(item);
    const status = getStatus(percentual);
    const clone = templateEl.content.cloneNode(true);

    const card = clone.querySelector(".shelf-item");
    const corredor = clone.querySelector(".shelf-corredor");
    const percentualEl = clone.querySelector(".shelf-percentual");
    const nome = clone.querySelector(".shelf-nome");
    const distancia = clone.querySelector(".shelf-distancia");
    const progressBar = clone.querySelector(".progress-bar");
    const updated = clone.querySelector(".shelf-updated");
    const btnRemover = clone.querySelector(".btn-remover");

    card.classList.add(`status-${status}`);
    corredor.textContent = item.corredor.toUpperCase();
    percentualEl.textContent = `${percentual}%`;
    nome.textContent = item.nome;
    distancia.textContent = `Distancia Sensor: ${item.distancia}cm`;
    progressBar.style.width = `${percentual}%`;
    progressBar.classList.add(`status-${status}`);
    updated.textContent = `Atualizado: ${tempoRelativo(item.atualizadoEm)}`;

    btnRemover.addEventListener("click", () => {
      prateleiras = prateleiras.filter((p) => p.id !== item.id);
      salvar();
      renderizar();
    });

    listaEl.appendChild(clone);
  });
}

function renderizarAlertas() {
  alertasEl.innerHTML = "";
  const itensComStatus = prateleiras
    .map((item) => {
      const percentual = getPercentual(item);
      return { item, percentual, status: getStatus(percentual) };
    })
    .filter(({ status }) => status !== "normal")
    .sort((a, b) => a.percentual - b.percentual);

  if (!itensComStatus.length) {
    alertasEl.innerHTML = "<p>Nenhum alerta ativo no momento.</p>";
    return;
  }

  itensComStatus.forEach(({ item, status }) => {
    const clone = templateAlertaEl.content.cloneNode(true);
    const alertItem = clone.querySelector(".alert-item");
    const title = clone.querySelector(".alert-title");
    const time = clone.querySelector(".alert-time");

    alertItem.classList.toggle("vazio", status === "vazio");
    title.textContent = `${labelStatus(status)}: ${item.nome} (${item.corredor})`;
    time.textContent = tempoRelativo(item.atualizadoEm);
    alertasEl.appendChild(clone);
  });
}

function renderizar() {
  atualizarKpis();
  renderizarPrateleiras();
  renderizarAlertas();
  renderizarSensores();
}

function setView(viewName) {
  Object.entries(views).forEach(([name, element]) => {
    element.classList.toggle("hidden", name !== viewName);
  });

  menuItems.forEach((item) => {
    item.classList.toggle("active", item.dataset.viewTarget === viewName);
  });

  viewOnlyElements.forEach((el) => {
    el.classList.toggle("hidden", el.dataset.viewOnly !== viewName);
  });

  pageTitle.textContent = viewName === "config-iot" ? "Configuracoes" : "Painel de Monitoramento";
}

function renderizarSensores() {
  sensorTableBodyEl.innerHTML = "";

  if (!sensores.length) {
    sensorTableBodyEl.innerHTML = '<tr><td colspan="5">Nenhum sensor cadastrado.</td></tr>';
    return;
  }

  sensores.forEach((sensor) => {
    const clone = sensorTemplateEl.content.cloneNode(true);
    clone.querySelector(".sensor-row-nome").textContent = sensor.nome;
    clone.querySelector(".sensor-row-id").textContent = sensor.sensorId;
    clone.querySelector(".sensor-row-corredor").textContent = sensor.corredor;
    clone.querySelector(".sensor-row-lado").textContent = sensor.lado === "B" ? "Lado B (Direita)" : "Lado A (Esquerda)";
    clone.querySelector(".sensor-row-categoria").textContent = sensor.categoria;

    const btn = clone.querySelector(".btn-remover-sensor");
    btn.addEventListener("click", () => {
      sensores = sensores.filter((item) => item.id !== sensor.id);
      salvarSensores();
      renderizarSensores();
      hubTotal.textContent = String(prateleiras.length);
    });

    sensorTableBodyEl.appendChild(clone);
  });
}

formEl.addEventListener("submit", (event) => {
  event.preventDefault();

  const nome = document.getElementById("nome").value.trim();
  const corredor = document.getElementById("corredor").value.trim();
  const distancia = Number(document.getElementById("distancia").value);
  const maximo = Number(document.getElementById("maximo").value);

  if (!nome || !corredor || Number.isNaN(distancia) || Number.isNaN(maximo)) return;

  prateleiras.unshift({
    id: crypto.randomUUID(),
    nome,
    corredor,
    distancia,
    maximo,
    atualizadoEm: new Date().toISOString()
  });

  salvar();
  renderizar();
  formEl.reset();
  document.getElementById("maximo").value = "120";
});

btnSimular.addEventListener("click", () => {
  prateleiras = prateleiras.map((item) => {
    const variacao = (Math.random() * 14 - 7);
    const novaDistancia = Math.max(0, Number((item.distancia + variacao).toFixed(1)));
    return {
      ...item,
      distancia: novaDistancia,
      atualizadoEm: new Date().toISOString()
    };
  });

  salvar();
  renderizar();
});

menuItems.forEach((item) => {
  item.addEventListener("click", (event) => {
    event.preventDefault();
    setView(item.dataset.viewTarget);
  });
});

sensorFormEl.addEventListener("submit", (event) => {
  event.preventDefault();

  const nome = document.getElementById("sensor-nome").value.trim();
  const sensorId = document.getElementById("sensor-id").value.trim();
  const mac = document.getElementById("sensor-mac").value.trim();
  const corredor = document.getElementById("sensor-corredor").value.trim();
  const lado = document.getElementById("sensor-lado").value;
  const categoria = document.getElementById("sensor-categoria").value.trim();

  if (!nome || !sensorId || !corredor || !categoria || !lado) return;

  sensores.unshift({
    id: crypto.randomUUID(),
    nome,
    sensorId,
    mac,
    corredor: `Corredor ${corredor.toUpperCase()}`,
    lado,
    categoria,
    status: "online"
  });

  salvarSensores();
  renderizarSensores();
  sensorFormEl.reset();
});

setView("visao-geral");
renderizar();
