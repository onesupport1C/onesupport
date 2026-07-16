// ============================================================
// Сайт-визитка 1С-интегратора — логика
// ============================================================

// Текущий год в футер
document.getElementById('year').textContent = new Date().getFullYear();

// ----- Мобильное меню -----
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');

navToggle.addEventListener('click', () => {
  navToggle.classList.toggle('active');
  navLinks.classList.toggle('open');
});

// Закрытие меню при клике по ссылке
navLinks.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => {
    navToggle.classList.remove('active');
    navLinks.classList.remove('open');
  });
});

// ----- Анимация появления блоков при скролле -----
const revealTargets = document.querySelectorAll(
  '.card, .why, .case, .stat, .section__head, .contact__text, .form'
);
revealTargets.forEach(el => el.classList.add('reveal'));

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

revealTargets.forEach(el => observer.observe(el));

// ----- Форма заявки -----
// Сейчас работает в «демо-режиме»: показывает сообщение.
// Чтобы заявки реально приходили — подключите Telegram-бота или
// сервис форм (например, Formspree). См. README.md, раздел «Контакты».
const form = document.getElementById('contactForm');
const note = document.getElementById('formNote');

form.addEventListener('submit', (e) => {
  e.preventDefault();

  const name = form.name.value.trim();
  const contact = form.contact.value.trim();

  if (!name || !contact) {
    note.style.color = 'var(--accent-3)';
    note.textContent = 'Заполните имя и контакт, пожалуйста.';
    return;
  }

  note.style.color = 'var(--accent)';
  note.textContent = 'Спасибо! Заявка отправлена. Свяжусь с вами в течение дня.';

  // TODO: заменить на реальную отправку (fetch на бота / сервис форм)
  form.reset();
});
