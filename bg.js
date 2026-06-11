(function () {
  /* ── Animated orbs ── */
  const orbs = document.createElement('div');
  orbs.className = 'bg-orbs';
  orbs.innerHTML = `
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <div class="bg-orb bg-orb-4"></div>
  `;
  document.body.insertBefore(orbs, document.body.firstChild);

  /* ── Cursor glow ── */
  const glow = document.createElement('div');
  glow.className = 'cursor-glow';
  document.body.appendChild(glow);

  // Track mouse directly with CSS custom properties — no lerp lag
  document.addEventListener('mousemove', (e) => {
    glow.style.left = e.clientX + 'px';
    glow.style.top  = e.clientY + 'px';
  });

  // Hide glow on touch devices
  document.addEventListener('touchstart', () => {
    glow.style.display = 'none';
  }, { once: true });
})();

// Ensure testimonials scroll continuously by duplicating items when needed
document.addEventListener('DOMContentLoaded', function () {
  try {
    var container = document.querySelector('.testimonials-container');
    if (!container) return;
    // avoid duplicating multiple times
    if (container.dataset.dup === '1') return;
    var items = Array.from(container.children);
    if (items.length === 0) return;
    // Append one clone of the list to create a seamless loop (A || A)
    items.forEach(function (node) {
      container.appendChild(node.cloneNode(true));
    });
    container.dataset.dup = '1';
  } catch (e) {
    console.error('Testimonials duplication failed', e);
  }
});
