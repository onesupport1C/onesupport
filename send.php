<?php
/**
 * send.php — обработчик формы заявки для сайта onesmaster.ru
 * Принимает POST-данные из формы и отправляет письмо на почту.
 *
 * Чтобы сменить почту получателя — поменяй $to ниже.
 */

header('Content-Type: application/json; charset=utf-8');

// === НАСТРОЙКА ===
$to = 'onesupport@ssuh.ru';          // куда приходят заявки
$subject = 'Новая заявка с сайта onesmaster.ru';

// Только AJAX
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'message' => 'Метод не поддерживается']);
  exit;
}

// Получаем и чистим данные
$name    = trim($_POST['name']    ?? '');
$contact = trim($_POST['contact'] ?? '');
$task    = trim($_POST['task']    ?? '');

// Проверка обязательных полей
if ($name === '' || $contact === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'message' => 'Заполните имя и контакт']);
  exit;
}

// Защита от спам-инъекций в заголовках
$name    = str_replace(["\r", "\n", "%0a", "%0d"], '', $name);
$contact = str_replace(["\r", "\n", "%0a", "%0d"], '', $contact);

// Тело письма
$body  = "Новая заявка с сайта onesmaster.ru\r\n\r\n";
$body .= "Имя: $name\r\n";
$body .= "Контакт: $contact\r\n";
$body .= "Задача:\r\n$task\r\n\r\n";
$body .= "Дата: " . date('d.m.Y H:i') . "\r\n";
$body .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'неизвестен');

// Заголовки письма
$headers  = "From: onesmaster.ru <no-reply@onesmaster.ru>\r\n";
$headers .= "Reply-To: noreply@onesmaster.ru\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// Отправка
$sent = mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);

if ($sent) {
  echo json_encode(['ok' => true]);
} else {
  http_response_code(500);
  echo json_encode(['ok' => false, 'message' => 'Ошибка отправки письма']);
}
