(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));
  const fmt = new Intl.NumberFormat('ro-RO');

  function initThumbs(){
    qsa('.product-gallery-set').forEach((set) => {
      const main = qs('.product-main img', set);
      const splideRoot = qs('.product-thumbs', set);
      if (!main || !splideRoot) return;

      splideRoot.addEventListener('click', (e)=>{
        const b = e.target.closest('.thumb');
        if (!b) return;
        const src = b.getAttribute('data-img');
        if (src) main.src = src;
        qsa('.thumb', splideRoot).forEach(t=>t.classList.remove('is-active'));
        b.classList.add('is-active');
      });

      if (window.Splide) {
        try {
          const spl = new Splide(splideRoot, { type: 'slide', perPage: 6, gap: '6px', pagination: false, arrows: false,
            breakpoints: { 1200:{ perPage:6 }, 992:{ perPage:5 }, 768:{ perPage:4 }, 576:{ perPage:3 } }
          });
          const barWrap = qs('.thumbs-progress', set);
          const bar = qs('.thumbs-progress-bar', set);
          if (bar && barWrap) {
            const update = () => {
              try {
                const end = spl.Components.Controller.getEnd() || 0;
                const ratio = end > 0 ? Math.min(1, (spl.index || 0) / end) : 0;
                const INDICATOR_PCT = 20;
                bar.style.width = INDICATOR_PCT + '%';
                const maxLeft = 100 - INDICATOR_PCT;
                bar.style.left = Math.min(maxLeft, Math.max(0, ratio * maxLeft)) + '%';
              } catch(_) {}
            };
            spl.on('mounted move', update);

            const seek = (clientX) => {
              const rect = barWrap.getBoundingClientRect();
              const x = Math.min(Math.max(clientX - rect.left, 0), rect.width);
              const ratio = rect.width ? x / rect.width : 0;
              const end = spl.Components.Controller.getEnd() || 0;
              spl.go(Math.round(ratio * end));
            };
            let isDown = false;
            barWrap.addEventListener('mousedown', (e)=>{ isDown = true; barWrap.classList.add('is-dragging'); seek(e.clientX); });
            document.addEventListener('mousemove', (e)=>{ if (isDown) seek(e.clientX); });
            document.addEventListener('mouseup', ()=>{ if (isDown){ isDown=false; barWrap.classList.remove('is-dragging'); } });
            barWrap.addEventListener('touchstart', (e)=>{ isDown = true; barWrap.classList.add('is-dragging'); seek(e.touches[0].clientX); }, { passive: true });
            document.addEventListener('touchmove', (e)=>{ if (isDown) seek(e.touches[0].clientX); }, { passive: true });
            document.addEventListener('touchend', ()=>{ if (isDown){ isDown=false; barWrap.classList.remove('is-dragging'); } });
          }

          spl.on('mounted', () => {
            try {
              const slides = splideRoot.querySelectorAll('.splide__slide');
              const per = spl.options.perPage || 6;
              const shouldShowBar = slides.length > per;
              if (barWrap) barWrap.style.display = shouldShowBar ? 'block' : 'none';
              const ul = splideRoot.querySelector('.splide__list');
              if (ul) { ul.style.display = shouldShowBar ? '' : 'flex'; ul.style.justifyContent = shouldShowBar ? '' : 'center'; }
            } catch(_) {}
          });
          spl.mount();
        } catch(_) {}
      }
    });
  }

  function swapGalleryBySize(size){
    qsa('.product-gallery-set').forEach((set) => {
      const active = set.getAttribute('data-size') === size;
      set.classList.toggle('is-active', active);
    });
  }

  function initSizes(){
    const picker = qs('#sizePicker');
    if (!picker) return;
    picker.addEventListener('click', (e)=>{
      const opt = e.target.closest('.size-option');
      if (!opt || !picker.contains(opt)) return;
      qsa('.size-option', picker).forEach(o=>o.classList.remove('is-selected'));
      opt.classList.add('is-selected');
      const price = parseInt(opt.getAttribute('data-price')||'0',10) || 0;
      
      const pWrap = opt.querySelector('.size-price');
      if (pWrap) pWrap.textContent = fmt.format(price) + ' RON';
      const old = opt.getAttribute('data-old');
      const oldEl = opt.querySelector('.size-price-old');
      if (old && oldEl) oldEl.textContent = fmt.format(parseInt(old,10)||0) + ' RON';
      
      const key = opt.getAttribute('data-size');
      swapGalleryBySize(key);
      recalcTotal();
    });
  }

  function initColorSwatches(){
    const nameOut = qs('#colorName');
    const wrap = qs('.color-swatches');
    if (!wrap) return;
    wrap.addEventListener('click', (e)=>{
      const s = e.target.closest('.swatch');
      if (!s) return;
      e.preventDefault();
      qsa('.swatch', wrap).forEach(x=>x.classList.remove('is-active'));
      s.classList.add('is-active');
      if (nameOut) nameOut.textContent = s.getAttribute('data-color-name') || '';
    });
  }

  function initAddons(){
    const grid = qs('#addonsGrid');
    if (!grid) return;
    
    grid.addEventListener('change', (e)=>{
      const chk = e.target.closest('input[type="checkbox"]');
      if (!chk) return;
      const card = chk.closest('.addon');
      if (!card) return;
      card.classList.toggle('is-selected', chk.checked);
    });
    grid.addEventListener('mouseover', (e)=>{
      const item = e.target.closest('.addon');
      if (!item || !grid.contains(item)) return;
      let pop = item.querySelector('.addon-pop');
      if (!pop) {
        pop = document.createElement('div');
        pop.className = 'addon-pop';
        const img = document.createElement('img');
        img.src = item.getAttribute('data-pop-img') || '';
        pop.appendChild(img);
        item.appendChild(pop);
      }
      pop.style.display = 'block';
    });
    grid.addEventListener('mouseout', (e)=>{
      const item = e.target.closest('.addon');
      if (!item || !grid.contains(item)) return;
      const pop = item.querySelector('.addon-pop');
      if (pop) pop.style.display = 'none';
    });
    grid.addEventListener('change', recalcTotal);
  }

  function getSelectedBasePrice(){
    const sel = qs('.size-option.is-selected');
    return parseInt(sel && sel.getAttribute('data-price') || '0', 10) || 0;
  }

  function recalcTotal(){
    const base = getSelectedBasePrice();
    let addons = 0;
    qsa('#addonsGrid .addon input:checked').forEach(chk => {
      const p = parseInt(chk.closest('.addon').getAttribute('data-price')||'0',10)||0;
      addons += p;
    });
    const total = base + addons;
    const out = qs('#prodTotal');
    if (out) out.textContent = fmt.format(total) + ' RON';
  }

  function initAddToCart(){
    const btn = qs('#productAddToCart');
    if (!btn) return;
    btn.addEventListener('click', ()=>{
      const title = qs('h1.brand-serif')?.textContent?.trim() || 'Produs';
      const price = getSelectedBasePrice();
      const mainImg = qs('.product-gallery-set.is-active .product-main img');
      if (window.FloraffeineCart && window.FloraffeineCart.add){
        window.FloraffeineCart.add({ id: title, title: title, price: price, imgEl: mainImg });
      }
    });
  }

  function initRelatedSlider(){
    if (!window.Splide) return;
    const root = document.getElementById('related-splide');
    if (!root) return;
    try {
      const splide = new Splide('#related-splide', {
        type: 'loop', perPage: 5, perMove: 1, gap: '22px', pagination: false, arrows: false,
        breakpoints: { 1200: { perPage: 4 }, 992: { perPage: 3 }, 576: { perPage: 2 } }
      });
      splide.mount();
      const prev = document.querySelector('.related-prev');
      const next = document.querySelector('.related-next');
      if (prev) prev.addEventListener('click', function(){ splide.go('<'); });
      if (next) next.addEventListener('click', function(){ splide.go('>'); });
    } catch(_) {}
  }

  function initProductGreeting(){
    const toggleBtn = document.querySelector('[data-prod-toggle="greet"]');
    const textarea = document.getElementById('productGreetText');
    if (!toggleBtn || !textarea) return;
    toggleBtn.addEventListener('click', function(){
      const isReadOnly = textarea.hasAttribute('readonly');
      if (isReadOnly) {
        textarea.removeAttribute('readonly');
        toggleBtn.textContent = 'Salvează';
        textarea.focus();
      } else {
        textarea.setAttribute('readonly', '');
        toggleBtn.textContent = 'Editează';
      }
    });
    
    textarea.removeAttribute('readonly');
    if (toggleBtn) toggleBtn.textContent = 'Salvează';
  }

  document.addEventListener('DOMContentLoaded', function(){
    if (!document.body.classList.contains('page-product')) return;
    initThumbs();
    initSizes();
    initColorSwatches();
    initAddons();
    recalcTotal();
    initAddToCart();
    initRelatedSlider();
    initProductGreeting();
  });
})();


