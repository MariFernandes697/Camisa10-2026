
// Busca pela barra de pesquisa
document.getElementById("formBusca").addEventListener("submit", function(e){

    e.preventDefault();

    const busca = document.getElementById('barraPesquisa').value.toLowerCase();
    const cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        const nomeProduto = card.dataset.nome.toLowerCase();
        // Busca os cards, se o nome digitado for igual ao elemento nome do card ele aparece dinamicamente
        if(nomeProduto.includes(busca)){
            // display block aparece, display nome irá esconder
            card.computedStyleMap.display = "block"; 
        }
        else{
            card.style.display = "none";
        }

    });
});


// Busca pelo elemento Filtrar por
document.addEventListener('DOMContentLoaded', () => {
  const formularioBusca = document.getElementById('formBusca');
  const campoBusca = document.getElementById('barraPesquisa');
  const seletorFiltro = document.getElementById('filtro');
  const botaoLimpar = document.getElementById('limparSeletor');

  const containerProdutos = document.querySelector('.produtos');
  const cardsOriginais = Array.from(document.querySelectorAll('.produtos .card'));

  const CARTOES_POR_LINHA = 4;

  let catalogo = cardsOriginais.map(card => {
    const nome = (card.dataset.nome || card.querySelector('h3')?.textContent || '').trim();
    const categoria = (card.dataset.categoria || '').trim();
    const precoRaw = card.dataset.preco || card.querySelector('.preco')?.textContent?.replace(/[^\d.,]/g, '') || '0';
    const preco = parseFloat(precoRaw.toString().replace(',', '.')) || 0;

    return {
      elemento: card,
      nome,
      nomeMinusculo: nome.toLowerCase(),
      categoria,
      categoriaMinusculo: categoria.toLowerCase(),
      preco
    };
  });

  const elementoSemResultados = document.createElement('div');
  elementoSemResultados.className = 'no-results';
  elementoSemResultados.style.padding = '28px';
  elementoSemResultados.style.textAlign = 'center';
  elementoSemResultados.style.color = '#ccc';
  elementoSemResultados.style.fontSize = '1.05rem';
  elementoSemResultados.textContent = 'Nenhum produto encontrado para a busca/filtragem aplicada.';

  function aplicarFiltros() {
    const termoBruto = campoBusca.value || '';
    const termo = termoBruto.trim().toLowerCase();

    let resultado = catalogo.filter(item => {
      if (!termo) return true;
      return item.nomeMinusculo.includes(termo) || item.categoriaMinusculo.includes(termo);
    });
    const modo = seletorFiltro?.value || 'categoria';
    if (modo === 'nome') {
      resultado.sort((a, b) => a.nomeMinusculo.localeCompare(b.nomeMinusculo, 'pt-BR', { sensitivity: 'base' }));
    } else if (modo === 'menor-preco') {
      resultado.sort((a, b) => a.preco - b.preco);
    } else if (modo === 'maior-preco') {
      resultado.sort((a, b) => b.preco - a.preco);
    } else if (modo === 'categoria') {
      resultado.sort((a, b) => {
        const cmpCat = a.categoriaMinusculo.localeCompare(b.categoriaMinusculo, 'pt-BR', { sensitivity: 'base' });
        if (cmpCat !== 0) return cmpCat;
        return a.nomeMinusculo.localeCompare(b.nomeMinusculo, 'pt-BR', { sensitivity: 'base' });
      });
    }

    renderizarResultados(resultado);
  }
  function renderizarResultados(itens) {
    while (containerProdutos.firstChild) containerProdutos.removeChild(containerProdutos.firstChild);

    if (!itens.length) {
      containerProdutos.appendChild(elementoSemResultados);
      return;
    }
    for (let i = 0; i < itens.length; i += CARTOES_POR_LINHA) {
      const grupo = itens.slice(i, i + CARTOES_POR_LINHA);
      const linhaCards = document.createElement('div');
      linhaCards.className = 'cards';

      grupo.forEach(item => {
        linhaCards.appendChild(item.elemento);
      });

      containerProdutos.appendChild(linhaCards);
    }
  }
  if (formularioBusca) {
    formularioBusca.addEventListener('submit', e => {
      e.preventDefault();
      aplicarFiltros();
    });
  }
  let debounceTimer = null;
  campoBusca?.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => aplicarFiltros(), 180);
  });

  seletorFiltro?.addEventListener('change', () => aplicarFiltros());

  botaoLimpar?.addEventListener('click', (e) => {
    e.preventDefault();
    if (seletorFiltro) seletorFiltro.value = 'categoria';
    if (campoBusca) campoBusca.value = '';
    aplicarFiltros();
  });

  aplicarFiltros();
});