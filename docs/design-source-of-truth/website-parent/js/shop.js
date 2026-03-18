(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));
  const fmt = new Intl.NumberFormat('ro-RO');

  function initSortDropdown(){
    const root = document.body;
    if (!root.classList.contains('page-shop')) return;
    const label = qs('.btn-sort .sort-value');
    qsa('.sort-dropdown .dropdown-item').forEach(btn => {
      btn.addEventListener('click', () => {
        qsa('.sort-dropdown .dropdown-item').forEach(b => b.classList.remove('is-selected'));
        btn.classList.add('is-selected');
        if (label) label.textContent = btn.textContent.trim();
        
      });
    });
  }

  function initFilterDrawer(){
    const open = qs('#filterOpen');
    const drawer = qs('#filterDrawer');
    const close = qs('#filterClose');
    if (!open || !drawer || !close) return;
    const scrim = qs('#drawerScrim');
    const show = () => { drawer.classList.add('is-open'); if (scrim){ scrim.classList.remove('is-open'); } /* keep page scrollable */ };
    const hide = () => { drawer.classList.remove('is-open'); if (scrim){ scrim.classList.remove('is-open'); } };
    open.addEventListener('click', () => {
      if (drawer.classList.contains('is-open')) hide(); else show();
    });
    close.addEventListener('click', hide);
    if (scrim) scrim.addEventListener('click', hide);
    document.addEventListener('keydown', (e)=>{ if (e.key==='Escape') hide(); });

    
    document.addEventListener('click', (e)=>{
      const isOpen = drawer.classList.contains('is-open');
      if (!isOpen) return;
      if (!drawer.contains(e.target) && !open.contains(e.target)) hide();
    });

    
    qsa('.filter-accordion', drawer).forEach(btn => {
      btn.addEventListener('click', ()=>{
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
      });
    });

    const min = qs('#priceMin');
    const max = qs('#priceMax');
    const minT = qs('.price-vals .min');
    const maxT = qs('.price-vals .max');
    function updateRange(){
      if (minT && min) minT.textContent = fmt.format(parseInt(min.value||'0',10)) + ' lei';
      if (maxT && max) maxT.textContent = fmt.format(parseInt(max.value||'0',10)) + ' lei';
    }
    if (min && max){ min.addEventListener('input', updateRange); max.addEventListener('input', updateRange); updateRange(); }
  }

  function computePrice(base, variant){
    const p = parseInt(base, 10) || 0;
    if (variant === 'double') return Math.round(p * 2 * 0.9); 
    if (variant === 'triple') return Math.round(p * 3 * 0.85); 
    return p;
  }

  function initVariants(){
    qsa('.shop-card').forEach(card => {
      const base = card.getAttribute('data-base-price') || '0';
      const priceEl = qs('.price', card);
      const thumb = qs('.product-thumb img', card);
      const meta = qs('.product-meta', card);
      if (!priceEl || !thumb || !meta) return;

      qsa('.variant', card).forEach(btn => {
        btn.addEventListener('click', (e)=>{
          e.preventDefault();
          const variant = btn.getAttribute('data-variant') || 'single';
          const src = btn.getAttribute('data-img');
          qsa('.variant', card).forEach(b=>{ b.classList.remove('is-selected'); b.setAttribute('aria-pressed','false'); });
          btn.classList.add('is-selected');
          btn.setAttribute('aria-pressed','true');
          if (src) thumb.src = src;

          const singlePrice = computePrice(base, 'single');
          const newPrice = computePrice(base, variant);
          const oldEl = qs('.price-old', card) || (variant !== 'single' ? (function(){ const s=document.createElement('span'); s.className='price-old'; meta.insertBefore(s, priceEl); return s; })() : null);

          if (variant === 'single'){
            const existingOld = qs('.price-old', card);
            if (existingOld) existingOld.remove();
            priceEl.textContent = fmt.format(singlePrice) + ' RON';
          } else {
            if (oldEl) oldEl.textContent = fmt.format(Math.round(parseInt(base,10) * (variant==='double'?2:3))) + ' RON';
            priceEl.textContent = fmt.format(newPrice) + ' RON';
          }
        });
      });

      
      card.addEventListener('click', (e)=>{
        if (e.target.closest('.variant-picker')) return; 
        const link = qs('.shop-card-link', card);
        if (link) { e.preventDefault(); link.click(); }
      });
    });
  }

  function initPagination(){
    const btn = qs('#showMore');
    const shown = qs('.shown-count');
    const total = qs('.total-count');
    if (!btn || !shown || !total) return;
    btn.addEventListener('click', ()=>{
      
      const s = parseInt(shown.textContent||'0',10) || 0;
      const t = parseInt(total.textContent||'0',10) || 0;
      const next = Math.min(t, s + 16);
      shown.textContent = String(next);
      if (next >= t) btn.disabled = true;
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    if (!document.body.classList.contains('page-shop')) return;
    initSortDropdown();
    initFilterDrawer();
    initVariants();
    
  });
})();


