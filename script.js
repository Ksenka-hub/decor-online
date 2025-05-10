  // ✅ Переключение табов (вход / регистрация)
  const loginTab = document.getElementById('loginTab');
  const registerTab = document.getElementById('registerTab');
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');

  loginTab?.addEventListener('click', () => {
    loginTab.classList.add('active');
    registerTab.classList.remove('active');
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
  });

  registerTab?.addEventListener('click', () => {
    registerTab.classList.add('active');
    loginTab.classList.remove('active');
    registerForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
  });

  // ✅ Панель настроек
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      document.querySelector('.settings-panel')?.classList.add('visible');
    }, 1000);
  });

  // ✅ Выпадающие настройки
  function toggleDropdown(id) {
    document.querySelectorAll('.settings-dropdown').forEach(drop => {
      if (drop.id !== id) drop.classList.remove('active');
    });
    const current = document.getElementById(id);
    current?.classList.toggle('active');
  }

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.settings-item')) {
      document.querySelectorAll('.settings-dropdown').forEach(drop => drop.classList.remove('active'));
    }
  });

  function setLanguage(lang) {
    document.getElementById('languageInput').value = lang;
    document.getElementById('languageForm').submit();
  }

  function setTheme(theme) {
    document.getElementById('themeInput').value = theme;
    document.getElementById('themeForm').submit();
  }

  // ✅ Проверка входа перед добавлением в корзину
  function checkLoginBeforeAdd() {
    const isLoggedIn = document.body.dataset.loggedIn === 'true';
    if (!isLoggedIn) {
      window.location.href = 'auth.php';
      return false;
    }
    return true;
  }

  // ✅ DOM Loaded
  document.addEventListener('DOMContentLoaded', () => {
    const cartCounter = document.getElementById('cartCount');
    const textAdded = "<?= $lang['added_to_cart'] ?>";
    const textAddToCart = "<?= $lang['add_to_cart'] ?>";

    // ✅ Добавление в корзину
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const button = form.querySelector('.add-to-cart-btn');
        const formData = new FormData(form);

        try {
          const response = await fetch('add_to_cart.php', {
            method: 'POST',
            body: formData
          });

          if (response.ok) {
            let count = parseInt(cartCounter?.textContent || 0);
            if (cartCounter) cartCounter.textContent = count + 1;
            button.disabled = true;
            const originalText = button.textContent;
            button.innerHTML = '✓ ' + textAdded;
            button.style.backgroundColor = '#88c459';

            setTimeout(() => {
              button.disabled = false;
              button.textContent = originalText;
              button.style.backgroundColor = '';
            }, 2000);
          }
        } catch {
          console.error('Ошибка сети');
        }
      });
    });

    // ✅ Модалка “Посмотреть поближе”
    document.querySelectorAll('.preview-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const card = btn.closest('.product-card');
        const imgSrc = card.querySelector('img').src;
        document.getElementById('modal-image').src = imgSrc;
        document.getElementById('modal').classList.add('active');
        document.body.classList.add('no-scroll');
      });
    });

    document.getElementById('modal-close')?.addEventListener('click', () => {
      document.getElementById('modal').classList.remove('active');
      document.body.classList.remove('no-scroll');
    });

    document.getElementById('modal')?.addEventListener('click', (e) => {
      if (e.target.id === 'modal') {
        document.getElementById('modal').classList.remove('active');
        document.body.classList.remove('no-scroll');
      }
    });

    // ✅ Анимация заголовков на главной
    const title = document.querySelector('.hero-title');
    if (title) {
      const subtitle = document.querySelector('.hero-subtitle');
      const quote = document.querySelector('.hero-quote');
      [title, subtitle, quote].forEach(el => el && (el.style.opacity = '0'));
      setTimeout(() => title.style.opacity = '1', 300);
      setTimeout(() => subtitle && (subtitle.style.opacity = '1'), 800);
      setTimeout(() => quote && (quote.style.opacity = '1'), 1300);
    }

    // ✅ WeChat модалка
    const wechatModal = document.getElementById('wechatModal');
    const wechatTrigger = document.querySelector('.wechat-footer');
    const wechatClose = document.querySelector('.close-wechat');

    wechatTrigger?.addEventListener('click', () => wechatModal?.classList.add('active'));
    wechatClose?.addEventListener('click', () => wechatModal?.classList.remove('active'));
    wechatModal?.addEventListener('click', (e) => {
      if (e.target === wechatModal) wechatModal.classList.remove('active');
    });

    // ✅ Звёзды при наведении на логотип
    function createStarEffect(target) {
      let interval;
      target.addEventListener('mouseenter', () => {
        interval = setInterval(() => {
          const star = document.createElement('div');
          star.classList.add('star');
          const rect = target.getBoundingClientRect();
          const x = rect.left + Math.random() * rect.width;
          const y = rect.top + window.scrollY;
          star.style.left = `${x}px`;
          star.style.top = `${y}px`;
          document.body.appendChild(star);
          setTimeout(() => star.remove(), 1200);
        }, 100);
      });
      target.addEventListener('mouseleave', () => clearInterval(interval), { once: true });
    }

    const logo = document.getElementById('logoLamp');
    const starTarget = document.querySelector('.starfall');
    logo && createStarEffect(logo);
    starTarget && createStarEffect(starTarget);

    // ✅ Модалка для отзыва
    const openReviewBtn = document.querySelector('.add-review-btn');
    const reviewModal = document.getElementById('reviewFormModal');
    const closeReview = document.querySelector('.close-review-form');
    const reviewForm = document.getElementById('submitReviewForm');
    const reviewStatus = document.querySelector('.form-status');

    openReviewBtn?.addEventListener('click', () => reviewModal?.classList.add('active'));
    closeReview?.addEventListener('click', () => {
      reviewModal?.classList.remove('active');
      reviewStatus?.classList.add('hidden');
      reviewForm?.reset();
    });
    reviewModal?.addEventListener('click', (e) => {
      if (e.target === reviewModal) {
        reviewModal.classList.remove('active');
        reviewStatus?.classList.add('hidden');
        reviewForm?.reset();
      }
    });

    reviewForm?.addEventListener('submit', async (e) => {
      e.preventDefault();
      reviewStatus.textContent = 'Отправка...';
      reviewStatus.classList.remove('hidden');

      const formData = new FormData(reviewForm);
      try {
        const res = await fetch(reviewForm.action, {
          method: 'POST',
          body: formData,
          headers: { 'Accept': 'application/json' }
        });

        if (res.ok) {
          reviewStatus.textContent = 'Спасибо за отзыв! 💜';
          reviewForm.reset();
        } else {
          reviewStatus.textContent = 'Ошибка при отправке. Попробуйте позже.';
        }
      } catch {
        reviewStatus.textContent = 'Ошибка сети.';
      }
    });

    // ✅ Превью аватара
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    avatarInput?.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          avatarPreview.src = ev.target.result;
        };
        reader.readAsDataURL(file);
      }
    });

    // ✅ Xiaohongshu модалка
    const xhsLink = document.querySelector('.xiaohongshu-link');
    const xhsModal = document.getElementById('xiaohongshuModal');
    const closeXhs = document.querySelector('.close-xhs');

    xhsLink?.addEventListener('click', (e) => {
      e.preventDefault();
      xhsModal?.classList.add('active');
    });

    closeXhs?.addEventListener('click', () => xhsModal?.classList.remove('active'));
    xhsModal?.addEventListener('click', (e) => {
      if (e.target === xhsModal) xhsModal.classList.remove('active');
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        wechatModal?.classList.remove('active');
        xhsModal?.classList.remove('active');
        reviewModal?.classList.remove('active');
      }
    });
  });