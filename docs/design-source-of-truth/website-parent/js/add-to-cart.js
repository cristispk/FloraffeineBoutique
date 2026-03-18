(function () {
  const qs = (sel, el = document) => el.querySelector(sel);
  const qsa = (sel, el = document) => Array.from(el.querySelectorAll(sel));
  const formatter = new Intl.NumberFormat('ro-RO');

  function getCartTargetRect() {
    const icon = document.querySelector('.nav-actions .dropdown i.ri-shopping-cart-line');
    const btn = icon ? icon.closest('button') : null;
    if (!btn) return null;

    const rect = (icon || btn).getBoundingClientRect();
    return {
      x: rect.left + rect.width / 2,
      y: rect.top + rect.height / 2,
    };
  }

  function incrementCartBadge() {
    const btn = document.querySelector('.nav-actions .dropdown i.ri-shopping-cart-line')?.closest('button');
    if (!btn) return;
    const badge = qs('.badge', btn) || (() => { const b = document.createElement('span'); b.className = 'badge'; b.textContent = '0'; btn.appendChild(b); return b; })();
    const current = parseInt(badge.textContent || '0', 10) || 0;
    badge.textContent = String(current + 1);
    badge.classList.remove('bump');

    void badge.offsetWidth;
    badge.classList.add('bump');
  }

  function flyToCart(fromImg) {
    const target = getCartTargetRect();
    if (!target || !fromImg) return;
    const rect = fromImg.getBoundingClientRect();

    const flying = document.createElement('div');
    flying.className = 'flying-thumb';
    const clone = document.createElement('img');
    clone.src = fromImg.src;
    flying.appendChild(clone);

    flying.style.width = rect.width + 'px';
    flying.style.height = rect.height + 'px';
    document.body.appendChild(flying);

    const startX = rect.left;
    const startY = rect.top;
    flying.style.left = startX + 'px';
    flying.style.top = startY + 'px';

    const deltaX = target.x - (rect.left + rect.width / 2);
    const deltaY = target.y - (rect.top + rect.height / 2);

    const container = document.getElementById('rec-splide') || fromImg.closest('.rec-grid');
    let cleanupGrid = () => {};
    if (container) {
      container.classList.add('is-animating');
      fromImg.closest('.product-card')?.classList.add('anim-source');
      cleanupGrid = () => {
        container.classList.remove('is-animating');
        fromImg.closest('.product-card')?.classList.remove('anim-source');
      };
    }

    const keyframes = [
      { transform: 'translate(0, 0) scale(1)', opacity: 1 },
      { transform: `translate(${deltaX * 0.4}px, ${deltaY * 0.4 - 40}px) scale(0.9)`, opacity: 0.98, offset: 0.4 },
      { transform: `translate(${deltaX * 0.8}px, ${deltaY * 0.8 - 10}px) scale(0.55)`, opacity: 0.92, offset: 0.8 },
      { transform: `translate(${deltaX}px, ${deltaY}px) scale(0.18)`, opacity: 0 }
    ];
    const animation = flying.animate(keyframes, { duration: 820, easing: 'cubic-bezier(0.18, 0.8, 0.2, 1)' });

    animation.onfinish = () => {
      flying.remove();
      incrementCartBadge();
      cleanupGrid();
      celebrateAtCart(target);
    };
  }

  function celebrateAtCart(target) {

    const ripple = document.createElement('div');
    ripple.className = 'cart-ripple';
    ripple.style.left = target.x + 'px';
    ripple.style.top = target.y + 'px';
    ripple.style.width = '22px';
    ripple.style.height = '22px';
    document.body.appendChild(ripple);
    ripple.classList.add('show');
    ripple.addEventListener('animationend', () => ripple.remove());

    const particles = 6;
    for (let i = 0; i < particles; i++) {
      const p = document.createElement('div');
      p.className = 'cart-particle';
      p.style.left = target.x + 'px';
      p.style.top = target.y + 'px';
      document.body.appendChild(p);
      const angle = (Math.PI * 2 * i) / particles;
      const dx = Math.cos(angle) * 26;
      const dy = Math.sin(angle) * 26;
      p.animate([
        { transform: 'translate(0,0) scale(1)', opacity: 0.9 },
        { transform: `translate(${dx}px, ${dy}px) scale(0.2)`, opacity: 0 }
      ], { duration: 520, easing: 'cubic-bezier(0.33, 1, 0.68, 1)' }).onfinish = () => p.remove();
    }
  }

  const cart = new Map(); 

  function renderCart() {
    const list = qs('.cart-items');
    const empty = qs('.cart-empty');
    const totalEl = qs('.cart-total-amount');
    if (!list || !empty || !totalEl) return;

    list.innerHTML = '';
    let total = 0;
    for (const [, item] of cart) {
      total += item.price * item.qty;
      const li = document.createElement('li');
      li.innerHTML = `
        <img src="${item.img}" alt="${item.title}"/>
        <div>
          <div class="cart-item-title">${item.title}</div>
          <div class="cart-item-meta">${item.qty} × ${formatter.format(item.price)} RON</div>
        </div>
        <div class="cart-item-price">${formatter.format(item.price * item.qty)} RON</div>
      `;
      list.appendChild(li);
    }
    empty.style.display = cart.size ? 'none' : '';
    totalEl.textContent = `${formatter.format(total)} RON`;
  }

  function onAddToCartClick(card) {
    const btn = qs('.add-to-cart-btn', card);
    const img = qs('.product-thumb img', card);
    const title = qs('.product-title', card)?.textContent?.trim() || 'Produs';
    const priceText = qs('.price', card)?.textContent?.replace(/[^0-9]/g, '') || '0';
    const price = parseInt(priceText, 10) || 0;
    const id = btn?.getAttribute('data-product-id') || title;

    const existing = cart.get(id);
    if (existing) existing.qty += 1; else cart.set(id, { id, title, price, qty: 1, img: img?.src || '' });
    renderCart();

    flyToCart(img);

    if (btn) {
      btn.innerHTML = '<i class="ri-check-line" aria-hidden="true"></i>';
      btn.setAttribute('aria-label', 'Adăugat');
      btn.removeAttribute('data-tooltip');
      btn.disabled = true;
    }
  }

  function initAddToCart() {
    qsa('.product-card').forEach((card) => {
      const btn = qs('.add-to-cart-btn', card);
      const img = qs('.product-thumb img', card);
      if (!btn || !img) return;
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        onAddToCartClick(card);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', initAddToCart);

  
  window.FloraffeineCart = window.FloraffeineCart || {};
  window.FloraffeineCart.add = function(opts){
    try {
      var id = opts && (opts.id || opts.title) || 'Produs';
      var title = opts && (opts.title || 'Produs');
      var price = parseInt(String(opts && opts.price || '0'), 10) || 0;
      var imgEl = opts && opts.imgEl;

      var existing = cart.get(id);
      if (existing) existing.qty += 1; else cart.set(id, { id: id, title: title, price: price, qty: 1, img: (imgEl && imgEl.src) || '' });
      renderCart();
      flyToCart(imgEl);
    } catch(_) {}
  };
})();



