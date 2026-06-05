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
