(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));

  const FLOWERS = [
    'Trandafir Roșu Premium','Trandafir Alb Elegant','Lalele Galbene de Primăvară','Crizanteme Albe Funerare','Garoafe Roz Delicate','Gerbera Portocalii Vesele','Iris Mov Regal','Floarea Soarelui Gigant','Margaretă Albastru','Bujor Mov','Crizantemă Alb','Lalea Verde','Floarea Soarelui Verde','Lalea Portocaliu','Bujor Portocaliu','Margaretă Verde','Crin Verde','Gypsophila Roz','Gerbera Verde','Frezii Alb','Narcisă Galben','Eucalipt Verde','Trandafir Galben','Bujor Alb','Narcisă Portocaliu','Garoafă Rosu','Lalea Mov','Crin'
  ];

  const nameToKey = (n)=> n.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu,'').replace(/\s+/g,'-');
  const priceOf = (n)=> {
    const base = 8 + (Math.abs(hash(n)) % 18); // 8..25 RON
    return base;
  }
  function hash(str){ let h=0; for (let i=0;i<str.length;i++){ h=((h<<5)-h)+str.charCodeAt(i); h|=0; } return h; }

  const state = { items: {} };

  function renderList(){
    const list = qs('#manualList');
    if (!list) return;
    const term = (qs('#manualSearch')?.value || '').trim().toLowerCase();
    const sort = qs('#manualSort')?.value || 'name-asc';
    let arr = FLOWERS.filter(f => f.toLowerCase().includes(term));
    arr.sort((a,b)=> sort==='name-asc' ? a.localeCompare(b) : b.localeCompare(a));
    list.innerHTML = arr.map(f => {
      const key = nameToKey(f);
      const price = priceOf(f);
      const img = `img/recommended-fb-${(Math.abs(hash(f))%10)+1}.png`;
      return `<li class="manual-item" data-name="${f}" data-key="${key}">
        <button class="thumb" data-img="${img}" aria-label="Previzualizează"><img src="${img}" alt="${f}" loading="lazy"/></button>
        <div class="mi-body">
          <div class="mi-title">${f}</div>
          <div class="mi-meta">${price} RON/buc</div>
        </div>
        <div class="mi-qty">
          <button class="q-btn q-minus" aria-label="Scade">−</button>
          <input class="q-input" type="number" min="0" value="${state.items[key]?.qty||0}" aria-label="Cantitate" />
          <button class="q-btn q-plus" aria-label="Adaugă">+</button>
        </div>
      </li>`;
    }).join('');

    // hover preview using a small popover similar to addons
    qsa('.manual-item .thumb').forEach(btn=>{
      btn.addEventListener('mouseenter', (e)=>showPreview(btn, e));
      btn.addEventListener('mousemove', (e)=>positionPreview(e));
      btn.addEventListener('mouseleave', hidePreview);
      btn.addEventListener('click', (e)=>{ e.preventDefault(); });
    });

    qsa('.manual-item').forEach(item=>{
      const key = item.dataset.key; const name = item.dataset.name;
      const minus = qs('.q-minus', item); const plus = qs('.q-plus', item); const input = qs('.q-input', item);
      const price = priceOf(name); const img = qs('.thumb', item).dataset.img;
      const setQty = (q)=>{ q = Math.max(0, q|0); state.items[key] = q ? {name, price, qty:q, img} : undefined; renderSummary(); input.value = q; };
      minus.addEventListener('click', ()=> { setQty((state.items[key]?.qty||0)-1); updateStickyTotals(); });
      plus.addEventListener('click', ()=> { setQty((state.items[key]?.qty||0)+1); updateStickyTotals(); });
      input.addEventListener('change', ()=> { setQty(parseInt(input.value,10)||0); updateStickyTotals(); });
    });
  }

  let preview;
  function showPreview(el, evt){
    hidePreview();
    const src = el.dataset.img; if (!src) return;
    preview = document.createElement('div');
    preview.className = 'ai-popover manual-preview';
    preview.style.display = 'block';
    preview.innerHTML = `<img src="${src}" alt=""/>`;
    document.body.appendChild(preview);
    if (evt) positionPreview(evt);
  }
  function positionPreview(evt){
    if (!preview) return;
    const pad = 12;
    const vw = window.innerWidth || document.documentElement.clientWidth;

    const w = preview.offsetWidth || 360;
    const h = preview.offsetHeight || 260;

    let x = evt.clientX + 16;
    let y = evt.clientY + 16;


    if (x + w + pad > vw) x = evt.clientX - w - 16;

    if (x < pad) x = pad;


    if (y + h + pad > vh) y = evt.clientY - h - 16;
    if (y < pad) y = pad;

    preview.style.left = x + 'px';
    preview.style.top = y + 'px';
  }
  function hidePreview(){ if (preview && preview.parentNode){ preview.parentNode.removeChild(preview); preview=null; } }

  function renderSummary(){
    const ul = qs('#manualSummary');
    const entries = Object.values(state.items).filter(Boolean);
    ul.innerHTML = entries.length ? entries.map(it => {
      const total = it.qty * it.price;
      return `<li class="sum-item" data-name="${it.name}">
        <img src="${it.img}" alt="${it.name}"/>
        <div class="sum-body"><div class="sum-title">${it.name}</div><div class="sum-meta">${it.qty} × ${it.price} RON</div></div>
        <div class="sum-right">
          <div class="sum-total">${total} RON</div>
          <div class="sum-qty">
            <button class="q-btn q-minus" aria-label="Scade">−</button>
            <input class="q-input" type="number" min="0" value="${it.qty}" aria-label="Cantitate" />
            <button class="q-btn q-plus" aria-label="Adaugă">+</button>
            <button class="sum-remove" data-key="${nameToKey(it.name)}" aria-label="Elimină" data-tooltip="Șterge">&times;</button>
          </div>
        </div>
      </li>`;
    }).join('') : '<li class="muted">Niciun element adăugat.</li>';

    qsa('.sum-remove', ul).forEach(btn=>{
      btn.addEventListener('click', ()=>{ delete state.items[btn.dataset.key]; renderList(); renderSummary(); });
    });

    qsa('.sum-item', ul).forEach(li=>{
      const name = li.dataset.name; const key = nameToKey(name);
      const minus = qs('.q-minus', li); const plus = qs('.q-plus', li); const input = qs('.q-input', li);
      const setQty = (q)=>{ q = Math.max(0, q|0); state.items[key] = q ? {name, price: priceOf(name), qty:q, img: qs('img', li).src} : undefined; renderList(); renderSummary(); };
      minus.addEventListener('click', ()=> { setQty((state.items[key]?.qty||0)-1); updateStickyTotals(); });
      plus.addEventListener('click', ()=> { setQty((state.items[key]?.qty||0)+1); updateStickyTotals(); });
      input.addEventListener('change', ()=> { setQty(parseInt(input.value,10)||0); updateStickyTotals(); });
    });

    const total = entries.reduce((s,it)=> s + it.qty*it.price, 0);
    const totalEl = qs('#manualTotal');
    if (totalEl) totalEl.textContent = `${total} RON`;
    updateStickyTotals();
  }

  function addToCart(){
    const entries = Object.values(state.items).filter(Boolean);
    if (!entries.length) return;
    const note = qs('#manualNote')?.value || '';
    const wrap = (qs('input[name="wrap"]:checked')?.value)||'hartie';
    const title = 'Buchet personalizat';
    const price = entries.reduce((s,it)=> s + it.qty*it.price, 0);
    const imgEl = null; // not used in animation here
    if (window.FloraffeineCart && typeof window.FloraffeineCart.add === 'function'){
      window.FloraffeineCart.add({ id: 'manual-' + Date.now(), title, price, imgEl, meta: { entries, wrap, note }});
    }
  }

  function init(){
    const s = qs('#manualSearch'); const sort = qs('#manualSort');
    if (s) s.addEventListener('input', renderList);
    if (sort) sort.addEventListener('change', renderList);
    const addBtn = qs('#manualAddToCart'); if (addBtn) addBtn.addEventListener('click', addToCart);
    // Sticky bar hooks
    const stickyAdd = qs('#manualAddToCartSticky'); if (stickyAdd) stickyAdd.addEventListener('click', addToCart);
    const sheetAdd = qs('#manualAddToCartSheet'); if (sheetAdd) sheetAdd.addEventListener('click', addToCart);
    const openSheet = qs('#manualOpenSheet'); const scrim = qs('#manualSheetScrim'); const sheet = qs('#manualSheet'); const closeSheet = qs('#manualCloseSheet');
    const toggleSheet = (open)=>{
      if (!sheet || !scrim) return;
      if (open){ sheet.classList.add('is-open'); scrim.classList.add('is-open'); sheet.setAttribute('aria-hidden','false'); }
      else { sheet.classList.remove('is-open'); scrim.classList.remove('is-open'); sheet.setAttribute('aria-hidden','true'); }
    };
    if (openSheet) openSheet.addEventListener('click', (e)=> {
      e.preventDefault();
      const right = document.querySelector('.manual-right');
      if (right){
        const y = right.getBoundingClientRect().top + window.pageYOffset - 110;
        window.scrollTo({ top: y, behavior: 'smooth' });
      }
    });
    if (closeSheet) closeSheet.addEventListener('click', ()=> toggleSheet(false));
    if (scrim) scrim.addEventListener('click', ()=> toggleSheet(false));
    renderList(); renderSummary();
    updateStickyTotals();

    // Observe main CTA visibility to hide sticky bar when CTA is on screen
    const mainCta = document.querySelector('#manualAddToCart');
    if ('IntersectionObserver' in window && mainCta){
      const io = new IntersectionObserver((entries)=>{
        isMainCtaVisible = entries[0]?.isIntersecting || false;
        applyStickyVisibility();
      }, { root: null, threshold: 0.01 });
      io.observe(mainCta);
    } else {
      // Fallback: check on scroll
      window.addEventListener('scroll', ()=>{
        const rect = mainCta?.getBoundingClientRect();
        isMainCtaVisible = !!rect && rect.top < window.innerHeight && rect.bottom > 0;
        applyStickyVisibility();
      }, { passive: true });
    }
  }

  let lastStickyTotal = 0;
  let isMainCtaVisible = false;
  function applyStickyVisibility(){
    const bar = qs('.manual-sticky-bar'); if (!bar) return;
    const body = document.body;
    const shouldShow = lastStickyTotal > 0 && !isMainCtaVisible;
    if (shouldShow){ bar.style.display = 'block'; body.classList.add('has-sticky-bar'); }
    else { bar.style.display = 'none'; body.classList.remove('has-sticky-bar'); }
  }
  function updateStickyTotals(){
    const total = Object.values(state.items).filter(Boolean).reduce((s,it)=> s + it.qty*it.price, 0);
    lastStickyTotal = total;
    const t1 = qs('#manualTotalSticky'); const t2 = qs('#manualTotalSheet');
    if (t1) t1.textContent = `${total} RON`;
    if (t2) t2.textContent = `${total} RON`;
    applyStickyVisibility();
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();


