// Chaves usadas para salvar e recuperar dados do localStorage.
// O primeiro grupo armazena as prateleiras/áreas monitoradas,
// o segundo armazena os sensores cadastrados.
const STORAGE_KEY = "prateleiras-iot-v2";
const SENSOR_STORAGE_KEY = "sensores-iot-v1";

// Elementos principais do DOM para renderizar a visão geral,
// alertas, cards de prateleira e o botão de simulação.
const listaEl = document.getElementById("lista-prateleiras");
const alertasEl = document.getElementById("lista-alertas");
const templateEl = document.getElementById("template-item");
const templateAlertaEl = document.getElementById("template-alerta");
const btnSimular = document.getElementById("btn-simular");

// KPIs e elementos de controle de navegação entre as views.
const kpiTotal = document.getElementById("kpi-total");
const kpiCheio = document.getElementById("kpi-cheio");
const kpiVazio = document.getElementById("kpi-vazio");
const hubTotal = document.getElementById("hub-total");
const pageTitle = document.getElementById("page-title");
const menuItems = Array.from(document.querySelectorAll(".menu-item[data-view-target]"));
const views = {
  "visao-geral": document.getElementById("view-visao-geral"),
  "config-iot": document.getElementById("view-config-iot"),
  "relatorios": document.getElementById("view-relatorios")
};
const viewOnlyElements = Array.from(document.querySelectorAll("[data-view-only]"));

// Elementos da tela de configuração de sensores e do painel de relatórios.
const sensorFormEl = document.getElementById("form-sensor");
const sensorTableBodyEl = document.getElementById("sensor-table-body");
const sensorTemplateEl = document.getElementById("template-sensor-row");
const sensorSubmitButtonEl = sensorFormEl.querySelector('button[type="submit"]');
const btnCancelarEdicaoSensorEl = document.getElementById("btn-cancelar-edicao-sensor");
const confirmModalEl = document.getElementById("confirm-modal");
const confirmModalMessageEl = document.getElementById("confirm-modal-message");
const confirmModalOkEl = document.getElementById("confirm-modal-ok");
const confirmModalCancelEl = document.getElementById("confirm-modal-cancel");
const reportSensorSelectEl = document.getElementById("report-sensor-select");
const reportPeriodButtons = Array.from(document.querySelectorAll(".period-btn"));
const reportTotalLeiturasEl = document.getElementById("report-total-leituras");
const reportTotalVazioEl = document.getElementById("report-total-vazio");
const reportTaxaVazioEl = document.getElementById("report-taxa-vazio");
const reportHorarioCriticoEl = document.getElementById("report-horario-critico");
const reportTempoMedioVazioEl = document.getElementById("report-tempo-medio-vazio");
const reportHourlyChartEl = document.getElementById("report-hourly-chart");
const reportWeekdayChartEl = document.getElementById("report-weekday-chart");
const reportDailyListEl = document.getElementById("report-daily-list");
const reportRankingEl = document.getElementById("report-ranking");
const reportInsightsEl = document.getElementById("report-insights");
const reportJsonOutputEl = document.getElementById("report-json-output");

// Dados iniciais exibidos quando o sistema não encontra nada salvo no localStorage.
const dadosIniciais = [
  { id: crypto.randomUUID(), corredor: "Corredor A1", nome: "Bebidas - Refrigerantes", distancia: 24, maximo: 120, atualizadoEm: new Date().toISOString() },
  { id: crypto.randomUUID(), corredor: "Corredor A1", nome: "Bebidas - Sucos", distancia: 77, maximo: 120, atualizadoEm: new Date().toISOString() },
  { id: crypto.randomUUID(), corredor: "Corredor A2", nome: "Limpeza - Sabao em Po", distancia: 108, maximo: 120, atualizadoEm: new Date().toISOString() },
  { id: crypto.randomUUID(), corredor: "Corredor A2", nome: "Limpeza - Amaciante", distancia: 117, maximo: 120, atualizadoEm: new Date().toISOString() }
];

// Estado global do aplicativo.
let prateleiras = carregar();
let sensores = carregarSensores();
let sensorEmEdicaoId = null;
let reportPeriodoAtual = "diario";

// Carrega as prateleiras do localStorage.
// Se não houver dados salvos, usa os dados iniciais definidos acima.
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

// Salva a lista atual de prateleiras no localStorage.
function salvar() {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(prateleiras));
}

// Carrega os sensores do localStorage.
// Se não houver sensores salvos, usa um conjunto de sensores padrão.
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

// Salva a lista atual de sensores no localStorage.
function salvarSensores() {
  localStorage.setItem(SENSOR_STORAGE_KEY, JSON.stringify(sensores));
}

// Normaliza o texto do corredor para sempre armazenar no formato "Corredor X".
function normalizarCorredor(valor) {
  const base = valor.replace(/^corredor\s*/i, "").trim().toUpperCase();
  return `Corredor ${base}`;
}

// Preenche o formulário de edição com os dados do sensor selecionado.
function preencherFormularioSensor(sensor) {
  document.getElementById("sensor-nome").value = sensor.nome;
  document.getElementById("sensor-id").value = sensor.sensorId;
  document.getElementById("sensor-mac").value = sensor.mac || "";
  document.getElementById("sensor-corredor").value = sensor.corredor.replace(/^Corredor\s*/i, "");
  document.getElementById("sensor-lado").value = sensor.lado === "B" ? "B" : "A";
  document.getElementById("sensor-categoria").value = sensor.categoria;
}

// Limpa o modo de edição de sensor e restaura o formulário para cadastro normal.
function limparModoEdicaoSensor() {
  sensorEmEdicaoId = null;
  sensorSubmitButtonEl.textContent = "Salvar Dispositivo";
  btnCancelarEdicaoSensorEl.classList.add("hidden");
  sensorFormEl.reset();
  document.getElementById("sensor-lado").value = "A";
}

// Mostra um modal de confirmação para ações sensíveis, como remoção.
// Retorna uma Promise que resolve com true ou false dependendo da escolha do usuário.
function confirmarAcao(mensagem) {
  return new Promise((resolve) => {
    confirmModalMessageEl.textContent = mensagem;
    confirmModalEl.classList.remove("hidden");
    confirmModalEl.setAttribute("aria-hidden", "false");

    const fechar = (confirmado) => {
      confirmModalEl.classList.add("hidden");
      confirmModalEl.setAttribute("aria-hidden", "true");
      confirmModalOkEl.removeEventListener("click", onConfirmar);
      confirmModalCancelEl.removeEventListener("click", onCancelar);
      confirmModalEl.removeEventListener("click", onBackdrop);
      resolve(confirmado);
    };

    const onConfirmar = () => fechar(true);
    const onCancelar = () => fechar(false);
    const onBackdrop = (event) => {
      if (event.target === confirmModalEl) fechar(false);
    };

    confirmModalOkEl.addEventListener("click", onConfirmar);
    confirmModalCancelEl.addEventListener("click", onCancelar);
    confirmModalEl.addEventListener("click", onBackdrop);
  });
}

// Gera um hash numérico simples a partir de texto.
// Usado para produzir históricos pseudo-aleatórios consistentes para cada prateleira.
function hashTexto(texto) {
  let hash = 0;
  for (let i = 0; i < texto.length; i += 1) {
    hash = (hash * 31 + texto.charCodeAt(i)) % 2147483647;
  }
  return hash;
}

// Função pseudo-aleatória determinística baseada em semente.
// Não é segura, mas é suficiente para gerar padrões de vazios no relatório.
function pseudoRandom(seed) {
  const valor = Math.sin(seed) * 10000;
  return valor - Math.floor(valor);
}

// Converte o período selecionado em quantidade de dias.
function obterDiasPorPeriodo(periodo) {
  if (periodo === "diario") return 1;
  if (periodo === "semanal") return 7;
  return 30;
}

// Formata minutos em uma string legível como "1h 20m" ou "30m".
function formatarDuracaoMinutos(min) {
  if (!Number.isFinite(min) || min <= 0) return "0m";
  const horas = Math.floor(min / 60);
  const minutos = Math.round(min % 60);
  if (horas === 0) return `${minutos}m`;
  return `${horas}h ${minutos}m`;
}

// Produz um histórico de leituras para uma prateleira ao longo de X dias.
// A função usa um hash determinístico para que o mesmo sensor gere padrões
// consistentes, mas ainda pareça variar ao longo do tempo.
function gerarHistoricoPrateleira(prateleira, dias) {
  const base = hashTexto(`${prateleira.id}-${prateleira.nome}`);
  const historico = [];
  const horasPico = [9, 10, 11, 17, 18, 19];

  for (let d = dias - 1; d >= 0; d -= 1) {
    const data = new Date();
    data.setHours(0, 0, 0, 0);
    data.setDate(data.getDate() - d);

    for (let h = 0; h < 24; h += 1) {
      const seed = base + d * 131 + h * 19;
      const pico = horasPico.includes(h) ? 0.24 : 0.09;
      const vazio = pseudoRandom(seed) < pico;
      historico.push({
        timestamp: new Date(data.getFullYear(), data.getMonth(), data.getDate(), h, 0, 0).toISOString(),
        data: data.toLocaleDateString("pt-BR"),
        diaSemana: data.toLocaleDateString("pt-BR", { weekday: "short" }),
        hora: h,
        vazio
      });
    }
  }

  return historico;
}

// Calcula todas as métricas de uma prateleira a partir do histórico.
// Isso inclui quantidade de leituras, ocorrências de vazio, total de minutos vazios,
// tempo médio de cada ocorrência e intervalo médio entre reposições.
function calcularMetricasPrateleira(prateleira, historico) {
  const leiturasVazias = historico.filter((h) => h.vazio);
  let ocorrencias = 0;
  let duracaoAtual = 0;
  let totalMin = 0;
  const duracoes = [];
  const inicios = [];

  historico.forEach((leitura, idx) => {
    if (leitura.vazio) {
      totalMin += 60;
      duracaoAtual += 60;
      const anterior = idx > 0 ? historico[idx - 1] : null;
      if (!anterior || !anterior.vazio) {
        ocorrencias += 1;
        inicios.push(new Date(leitura.timestamp).getTime());
      }
    } else if (duracaoAtual > 0) {
      duracoes.push(duracaoAtual);
      duracaoAtual = 0;
    }
  });

  if (duracaoAtual > 0) duracoes.push(duracaoAtual);
  const tempoMedioOcorrencia = duracoes.length ? totalMin / duracoes.length : 0;

  const intervalos = [];
  for (let i = 1; i < inicios.length; i += 1) {
    intervalos.push((inicios[i] - inicios[i - 1]) / (1000 * 60));
  }
  const intervaloMedioReposicao = intervalos.length
    ? intervalos.reduce((soma, valor) => soma + valor, 0) / intervalos.length
    : 0;

  return {
    prateleiraId: prateleira.id,
    prateleiraNome: prateleira.nome,
    corredor: prateleira.corredor,
    totalLeituras: historico.length,
    totalVazioLeituras: leiturasVazias.length,
    ocorrenciasVazio: ocorrencias,
    tempoTotalVazioMin: totalMin,
    tempoMedioVazioOcorrenciaMin: tempoMedioOcorrencia,
    intervaloMedioReposicaoMin: intervaloMedioReposicao
  };
}

// Renderiza todo o painel de relatórios, incluindo filtros, gráficos e métricas.
function renderizarRelatorios() {
  reportSensorSelectEl.innerHTML = "";
  const todasOption = document.createElement("option");
  todasOption.value = "__all__";
  todasOption.textContent = "Todos os sensores";
  reportSensorSelectEl.appendChild(todasOption);

  if (!prateleiras.length) {
    reportTotalLeiturasEl.textContent = "0";
    reportTotalVazioEl.textContent = "0";
    reportTaxaVazioEl.textContent = "Representa 0% das leituras";
    reportHorarioCriticoEl.textContent = "0m";
    reportTempoMedioVazioEl.textContent = "Tempo medio por ocorrencia: --";
    reportHourlyChartEl.innerHTML = "<p>Cadastre prateleiras para gerar relatorios.</p>";
    reportWeekdayChartEl.innerHTML = "";
    reportDailyListEl.innerHTML = "";
    reportRankingEl.innerHTML = "";
    reportInsightsEl.innerHTML = "<li>Sem dados suficientes para analise.</li>";
    reportJsonOutputEl.textContent = "{}";
    return;
  }

  sensores.forEach((sensor) => {
    const option = document.createElement("option");
    option.value = sensor.id;
    option.textContent = `${sensor.nome} (${sensor.corredor})`;
    reportSensorSelectEl.appendChild(option);
  });

  if (!sensores.some((sensor) => sensor.id === reportSensorSelectEl.value) && reportSensorSelectEl.value !== "__all__") {
    reportSensorSelectEl.value = "__all__";
  }

  const periodo = reportPeriodoAtual;
  const dias = obterDiasPorPeriodo(periodo);
  const selectedId = reportSensorSelectEl.value;
  const selectedSensor = sensores.find((sensor) => sensor.id === selectedId);
  const alvo = selectedId === "__all__"
    ? prateleiras
    : prateleiras.filter((p) => p.nome === selectedSensor?.categoria && p.corredor === selectedSensor?.corredor);

  const historicoTotal = [];
  const metricasPorPrateleira = alvo.map((prateleira) => {
    const historico = gerarHistoricoPrateleira(prateleira, dias);
    historico.forEach((item) => historicoTotal.push({ ...item, prateleiraId: prateleira.id, prateleiraNome: prateleira.nome }));
    return calcularMetricasPrateleira(prateleira, historico);
  });

  const totalLeituras = metricasPorPrateleira.reduce((soma, m) => soma + m.totalLeituras, 0);
  const totalVazio = metricasPorPrateleira.reduce((soma, m) => soma + m.totalVazioLeituras, 0);
  const tempoTotalVazio = metricasPorPrateleira.reduce((soma, m) => soma + m.tempoTotalVazioMin, 0);
  const ocorrenciasTotais = metricasPorPrateleira.reduce((soma, m) => soma + m.ocorrenciasVazio, 0);
  const intervaloMedioGlobal = metricasPorPrateleira
    .map((m) => m.intervaloMedioReposicaoMin)
    .filter((v) => v > 0);

  const tempoMedioOcorrenciaGlobal = ocorrenciasTotais ? tempoTotalVazio / ocorrenciasTotais : 0;
  const intervaloMedio = intervaloMedioGlobal.length
    ? intervaloMedioGlobal.reduce((s, v) => s + v, 0) / intervaloMedioGlobal.length
    : 0;

  const taxaVazio = totalLeituras ? Math.round((totalVazio / totalLeituras) * 100) : 0;

  const porHora = Array.from({ length: 24 }, (_, h) => ({
    hora: h,
    vazios: historicoTotal.filter((item) => item.hora === h && item.vazio).length
  }));

  const diasSemanaOrdem = ["dom.", "seg.", "ter.", "qua.", "qui.", "sex.", "sab."];
  const porDiaSemana = diasSemanaOrdem.map((dia) => ({
    dia,
    vazios: historicoTotal.filter((item) => item.diaSemana.toLowerCase().startsWith(dia.slice(0, 3)) && item.vazio).length
  }));

  const horaCritica = porHora.reduce((atual, item) => (item.vazios > atual.vazios ? item : atual), porHora[0]);

  reportTotalLeiturasEl.textContent = String(totalLeituras);
  reportTotalVazioEl.textContent = String(ocorrenciasTotais);
  reportTaxaVazioEl.textContent = `Representa ${taxaVazio}% das leituras`;
  reportHorarioCriticoEl.textContent = formatarDuracaoMinutos(tempoTotalVazio);
  reportTempoMedioVazioEl.textContent = `Tempo medio por ocorrencia: ${formatarDuracaoMinutos(tempoMedioOcorrenciaGlobal)}`;

  const maiorHora = Math.max(...porHora.map((item) => item.vazios), 1);
  reportHourlyChartEl.innerHTML = "";
  porHora.forEach((item) => {
    const row = document.createElement("div");
    row.className = "hour-row";
    const largura = Math.round((item.vazios / maiorHora) * 100);
    const intensidade = item.vazios === 0 ? "baixo" : item.vazios >= horaCritica.vazios * 0.7 ? "alto" : "medio";
    row.innerHTML = `
      <span>${String(item.hora).padStart(2, "0")}:00</span>
      <div class="hour-bar-bg"><div class="hour-bar" style="width:${largura}%"></div></div>
      <span class="hour-label">${item.vazios} vez(es) - risco ${intensidade}</span>
    `;
    reportHourlyChartEl.appendChild(row);
  });

  const maiorDia = Math.max(...porDiaSemana.map((d) => d.vazios), 1);
  reportWeekdayChartEl.innerHTML = "";
  porDiaSemana.forEach((item) => {
    const row = document.createElement("div");
    row.className = "hour-row";
    const largura = Math.round((item.vazios / maiorDia) * 100);
    row.innerHTML = `
      <span>${item.dia}</span>
      <div class="hour-bar-bg"><div class="hour-bar" style="width:${largura}%"></div></div>
      <span class="hour-label">${item.vazios} vez(es)</span>
    `;
    reportWeekdayChartEl.appendChild(row);
  });

  const porDiaMap = new Map();
  historicoTotal.forEach((item) => {
    if (!porDiaMap.has(item.data)) {
      porDiaMap.set(item.data, { total: 0, vazio: 0 });
    }
    const dia = porDiaMap.get(item.data);
    dia.total += 1;
    if (item.vazio) dia.vazio += 1;
  });

  reportDailyListEl.innerHTML = "";
  Array.from(porDiaMap.entries()).reverse().forEach(([data, valores]) => {
    const item = document.createElement("article");
    item.className = "daily-item";
    const taxaDia = Math.round((valores.vazio / valores.total) * 100);
    item.innerHTML = `
      <strong>${data}</strong>
      <small>Ocorrencias de vazio: ${valores.vazio} / ${valores.total} leituras (${taxaDia}%)</small>
    `;
    reportDailyListEl.appendChild(item);
  });

  const diasComVazio = Array.from(porDiaMap.values()).filter((dia) => dia.vazio > 0).length;
  const mediaVaziosDia = Math.round(ocorrenciasTotais / Math.max(1, porDiaMap.size));
  const horarioInicioCritico = String(horaCritica.hora).padStart(2, "0");
  const horarioFimCritico = String((horaCritica.hora + 1) % 24).padStart(2, "0");
  const diaCritico = porDiaSemana.reduce((atual, item) => (item.vazios > atual.vazios ? item : atual), porDiaSemana[0]);

  const ranking = [...metricasPorPrateleira]
    .sort((a, b) => b.ocorrenciasVazio - a.ocorrenciasVazio)
    .slice(0, 5);

  reportRankingEl.innerHTML = "";
  ranking.forEach((item, idx) => {
    const card = document.createElement("article");
    card.className = "daily-item";
    card.innerHTML = `
      <strong>#${idx + 1} ${item.prateleiraNome}</strong>
      <small>${item.ocorrenciasVazio} ocorrencia(s), tempo vazio ${formatarDuracaoMinutos(item.tempoTotalVazioMin)}, intervalo medio reposicao ${formatarDuracaoMinutos(item.intervaloMedioReposicaoMin)}</small>
    `;
    reportRankingEl.appendChild(card);
  });

  reportInsightsEl.innerHTML = `
    <li><strong>Janela critica:</strong> maior risco entre ${horarioInicioCritico}:00 e ${horarioFimCritico}:00.</li>
    <li><strong>Dia critico:</strong> maior incidencia em ${diaCritico.dia}.</li>
    <li><strong>Frequencia:</strong> em media, ${mediaVaziosDia} ocorrencia(s) de vazio por dia no periodo.</li>
    <li><strong>Impacto:</strong> o sensor ficou vazio em ${diasComVazio} de ${porDiaMap.size} dias analisados.</li>
    <li><strong>Acao sugerida:</strong> reforcar reposicao antes das ${horarioInicioCritico}:00 e aumentar cobertura em ${diaCritico.dia}.</li>
    <li><strong>Intervalo medio entre reposicoes:</strong> ${formatarDuracaoMinutos(intervaloMedio)}.</li>
  `;

  const saidaEstruturada = {
    periodo,
    diasAnalisados: dias,
    escopo: selectedId === "__all__" ? "todas_prateleiras" : "prateleira_unica",
    resumoGeral: {
      totalLeituras,
      ocorrenciasVazio: ocorrenciasTotais,
      taxaVazioPercentual: taxaVazio,
      tempoTotalVazioMin: tempoTotalVazio,
      tempoMedioVazioOcorrenciaMin: Number(tempoMedioOcorrenciaGlobal.toFixed(2)),
      intervaloMedioReposicaoMin: Number(intervaloMedio.toFixed(2))
    },
    metricasPrincipaisPorPrateleira: metricasPorPrateleira,
    padroes: {
      porHora,
      porDiaSemana,
      horarioMaisCritico: horaCritica.hora,
      diaMaisCritico: diaCritico.dia
    },
    rankingPrateleirasCriticas: ranking,
    insights: [
      `Reforcar reposicao entre ${horarioInicioCritico}:00-${horarioFimCritico}:00.`,
      `Acompanhar operacao em ${diaCritico.dia} por ser o dia mais critico.`,
      "Priorizar prateleiras do topo do ranking nas rotas de reposicao."
    ]
  };

  reportJsonOutputEl.textContent = JSON.stringify(saidaEstruturada, null, 2);
}

// Calcula o percentual de preenchimento da prateleira baseado na distância medida.
function getPercentual(item) {
  const maximo = Number(item.maximo);
  if (maximo <= 0) return 0;
  const preenchimento = ((maximo - Number(item.distancia)) / maximo) * 100;
  return Math.max(0, Math.min(100, Math.round(preenchimento)));
}

// Retorna o estado textual da prateleira conforme o percentual calculado.
function getStatus(percentual) {
  if (percentual < 15) return "vazio";
  return "cheio";
}

// Retorna a label amigável usada nos alertas.
function labelStatus(status) {
  if (status === "vazio") return "Vazio";
  return "Cheio";
}

// Converte um timestamp ISO em um texto simples de tempo relativo.
function tempoRelativo(iso) {
  const diffMin = Math.max(0, Math.floor((Date.now() - new Date(iso).getTime()) / 60000));
  if (diffMin <= 1) return "Ha 1 min";
  return `Ha ${diffMin} min`;
}

// Atualiza os KPIs do dashboard com base no estado atual das prateleiras.
function atualizarKpis() {
  const totais = prateleiras.reduce((acc, item) => {
    const status = getStatus(getPercentual(item));
    acc[status] += 1;
    return acc;
  }, { cheio: 0, vazio: 0 });

  const total = prateleiras.length;
  kpiTotal.textContent = `${total} secoes`;
  kpiCheio.textContent = String(totais.cheio);
  kpiVazio.textContent = String(totais.vazio);
  hubTotal.textContent = String(total);
}

// Renderiza a lista de prateleiras/áreas monitoradas na visão geral.
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
    const nome = clone.querySelector(".shelf-nome");
    const updated = clone.querySelector(".shelf-updated");
    const btnRemover = clone.querySelector(".btn-remover");

    card.classList.add(`status-${status}`);
    corredor.textContent = item.corredor.toUpperCase();
    nome.textContent = item.nome;
    updated.textContent = `Atualizado: ${tempoRelativo(item.atualizadoEm)}`;

    btnRemover.addEventListener("click", async () => {
      const confirmado = await confirmarAcao(`Deseja remover a secao "${item.nome}"?`);
      if (!confirmado) return;
      prateleiras = prateleiras.filter((p) => p.id !== item.id);
      salvar();
      renderizar();
    });

    listaEl.appendChild(clone);
  });
}

// Mostra os alertas de prateleiras vazias com prioridade de reposição.
function renderizarAlertas() {
  alertasEl.innerHTML = "";
  const itensComStatus = prateleiras
    .map((item) => {
      const percentual = getPercentual(item);
      return { item, percentual, status: getStatus(percentual) };
    })
    .filter(({ status }) => status === "vazio")
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

// Função principal que re-renderiza todas as partes da interface.
function renderizar() {
  atualizarKpis();
  renderizarPrateleiras();
  renderizarAlertas();
  renderizarSensores();
  renderizarRelatorios();
}

// Alterna entre as views do aplicativo: visão geral, configuração e relatórios.
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

  if (viewName === "config-iot") {
    pageTitle.textContent = "Configuracoes";
    return;
  }
  if (viewName === "relatorios") {
    pageTitle.textContent = "Relatorios Inteligentes";
    return;
  }
  pageTitle.textContent = "Painel de Monitoramento";
}

// Renderiza a tabela de sensores cadastrados na view de configuração.
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

    const btnEditar = clone.querySelector(".btn-editar-sensor");
    const btnRemover = clone.querySelector(".btn-remover-sensor");

    btnEditar.addEventListener("click", () => {
      sensorEmEdicaoId = sensor.id;
      preencherFormularioSensor(sensor);
      sensorSubmitButtonEl.textContent = "Atualizar Dispositivo";
      btnCancelarEdicaoSensorEl.classList.remove("hidden");
    });

    btnRemover.addEventListener("click", async () => {
      const confirmado = await confirmarAcao(`Deseja remover o sensor "${sensor.nome}" (${sensor.sensorId})?`);
      if (!confirmado) return;
      sensores = sensores.filter((item) => item.id !== sensor.id);
      salvarSensores();
      renderizarSensores();
      renderizarRelatorios();
      if (sensorEmEdicaoId === sensor.id) limparModoEdicaoSensor();
      hubTotal.textContent = String(prateleiras.length);
    });

    sensorTableBodyEl.appendChild(clone);
  });
}

// Evento do botão de simulação: altera valores de distância para testar o painel.
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

// Navegação do menu lateral: troca entre as views sem recarregar a página.
menuItems.forEach((item) => {
  item.addEventListener("click", (event) => {
    event.preventDefault();
    setView(item.dataset.viewTarget);
  });
});

// Salva ou atualiza um sensor quando o formulário de configuração é enviado.
sensorFormEl.addEventListener("submit", (event) => {
  event.preventDefault();

  const nome = document.getElementById("sensor-nome").value.trim();
  const sensorId = document.getElementById("sensor-id").value.trim();
  const mac = document.getElementById("sensor-mac").value.trim();
  const corredor = document.getElementById("sensor-corredor").value.trim();
  const lado = document.getElementById("sensor-lado").value;
  const categoria = document.getElementById("sensor-categoria").value.trim();

  if (!nome || !sensorId || !corredor || !categoria || !lado) return;

  if (sensorEmEdicaoId) {
    sensores = sensores.map((sensor) => {
      if (sensor.id !== sensorEmEdicaoId) return sensor;
      return {
        ...sensor,
        nome,
        sensorId,
        mac,
        corredor: normalizarCorredor(corredor),
        lado,
        categoria
      };
    });
  } else {
    sensores.unshift({
      id: crypto.randomUUID(),
      nome,
      sensorId,
      mac,
      corredor: normalizarCorredor(corredor),
      lado,
      categoria,
      status: "online"
    });
  }

  salvarSensores();
  renderizarSensores();
  renderizarRelatorios();
  limparModoEdicaoSensor();
});

btnCancelarEdicaoSensorEl.addEventListener("click", () => {
  limparModoEdicaoSensor();
});

// Atualiza o relatório quando o filtro de sensor muda.
reportSensorSelectEl.addEventListener("change", () => {
  renderizarRelatorios();
});

// Atualiza o relatório quando o período diário/semanal/mensal é alterado.
reportPeriodButtons.forEach((button) => {
  button.addEventListener("click", () => {
    reportPeriodoAtual = button.dataset.period;
    reportPeriodButtons.forEach((btn) => btn.classList.toggle("active", btn === button));
    renderizarRelatorios();
  });
});

setView("visao-geral");
renderizar();
