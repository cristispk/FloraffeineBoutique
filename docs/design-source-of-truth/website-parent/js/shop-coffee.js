(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));
  const fmt = new Intl.NumberFormat('ro-RO');

  function initFilterDrawer(){
    const open = qs('#filterOpen');
    const drawer = qs('#filterDrawer');
    const close = qs('#filterClose');
    const scrim = qs('#drawerScrim');
    if (!open || !drawer || !close) return;
    const show = () => { drawer.classList.add('is-open'); if (scrim){ scrim.classList.remove('is-open'); } };
    const hide = () => { drawer.classList.remove('is-open'); if (scrim){ scrim.classList.remove('is-open'); } };
    open.addEventListener('click', ()=>{ drawer.classList.contains('is-open') ? hide() : show(); });
    close.addEventListener('click', hide);
    if (scrim) scrim.addEventListener('click', hide);
    document.addEventListener('keydown', (e)=>{ if (e.key==='Escape') hide(); });

    qsa('.filter-accordion', drawer).forEach(btn => {
      btn.addEventListener('click', ()=>{
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
      });
    });
  }

  function initCategoryFilter(){
    const catWrap = qs('#coffeeCats');
    if (!catWrap) return;
    const products = qsa('.shop-grid .shop-card');
    function apply(){
      const checks = qsa('input[type="checkbox"]', catWrap).filter(c=>c.checked);
      const hasAll = checks.some(c=>c.dataset.cat==='all');
      const cats = hasAll ? ['cafea','prajituri','gustare'] : checks.map(c=>c.dataset.cat);
      const min = parseFloat((qs('#priceMin')?.value)||'0') || 0;
      const max = parseFloat((qs('#priceMax')?.value)||'9999') || 9999;
      products.forEach(card => {
        const cat = card.getAttribute('data-category');
        const price = parseFloat(card.getAttribute('data-price')||'0') || 0;
        const show = cats.includes(cat) && price >= min && price <= max;
        card.style.display = show ? '' : 'none';
      });
    }
    catWrap.addEventListener('change', (e)=>{
      if (e.target && e.target.matches('input[type="checkbox"]')){
        if (e.target.dataset.cat === 'all'){
          qsa('input[type="checkbox"]', catWrap).forEach(c=>{ if (c!==e.target) c.checked = false; });
        } else {
          const all = qs('input[data-cat="all"]', catWrap); if (all) all.checked = false;
        }
        
        const anyChecked = qsa('input[type="checkbox"]', catWrap).some(c => c.checked);
        if (!anyChecked) {
          const all = qs('input[data-cat="all"]', catWrap);
          if (all) all.checked = true;
        }
        apply();
      }
    });
    apply();
  }

  function initPriceRange(){
    const min = qs('#priceMin');
    const max = qs('#priceMax');
    const minT = qs('.price-vals .min');
    const maxT = qs('.price-vals .max');
    const fmt = new Intl.NumberFormat('ro-RO');
    function updateLabels(){
      if (minT && min) minT.textContent = fmt.format(parseInt(min.value||'0',10)) + ' lei';
      if (maxT && max) maxT.textContent = fmt.format(parseInt(max.value||'0',10)) + ' lei';
    }
    function reapply(){ initCategoryFilter(); updateLabels(); }
    if (min) min.addEventListener('input', reapply);
    if (max) max.addEventListener('input', reapply);
    updateLabels();
  }

  function initAddModal(){
    const modalEl = qs('#pickupModal');
    if (!modalEl) return;
    const modal = new bootstrap.Modal(modalEl);
    let pendingItem = null;

    qsa('.add-coffee-btn').forEach(btn => {
      btn.addEventListener('click', (e)=>{
        e.preventDefault();
        const card = btn.closest('.shop-card');
        const title = qs('.product-title', card)?.textContent?.trim() || 'Produs';
        const priceText = qs('.price', card)?.textContent?.replace(/[^0-9]/g,'') || '0';
        const price = parseInt(priceText, 10) || 0;
        const imgEl = qs('.product-thumb img', card);
        pendingItem = { id: btn.dataset.id || title, title, price, imgEl };
        const h = qs('#pickupTitle'); if (h) h.textContent = 'Adaugă: ' + title;
        const t = qs('#pickupTime'); if (t) { t.value = ''; t.focus(); }
        const note = qs('#pickupNote'); if (note) note.value = '';
        modal.show();
      });
    });

    const confirm = qs('#confirmAddToCart');
    if (confirm){
      confirm.addEventListener('click', ()=>{
        const t = qs('#pickupTime');
        if (!t || !t.value) { try { confirm.classList.add('shake'); setTimeout(()=>confirm.classList.remove('shake'),500); } catch(_){} return; }
        if (window.FloraffeineCart && window.FloraffeineCart.add && pendingItem){
          window.FloraffeineCart.add({ id: pendingItem.id, title: pendingItem.title, price: pendingItem.price, imgEl: pendingItem.imgEl });
        }
        modal.hide();
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function(){
    if (!document.body.classList.contains('page-shop-coffee')) return;
    initFilterDrawer();
    initCategoryFilter();
    initPriceRange();
    initAddModal();
  });
})();


