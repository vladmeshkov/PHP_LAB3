// Проверка форм на стороне клиента: невалидная форма на сервер не уходит.
// Серверные проверки остаются, поэтому без JavaScript всё работает так же, только через перезагрузку.
(function () {
    'use strict';

    function luhnValid(digits) {
        if (digits.length < 13 || digits.length > 19) {
            return false;
        }
        var sum = 0;
        for (var i = 0; i < digits.length; i++) {
            var digit = Number(digits.charAt(digits.length - 1 - i));
            if (i % 2 === 1) {
                digit *= 2;
                if (digit > 9) {
                    digit -= 9;
                }
            }
            sum += digit;
        }
        return sum % 10 === 0;
    }

    function expiryValid(value) {
        var match = /^(0[1-9]|1[0-2])\/(\d{2})$/.exec(value);
        if (!match) {
            return false;
        }
        var endOfMonth = new Date(2000 + Number(match[2]), Number(match[1]), 0, 23, 59, 59);
        return endOfMonth >= new Date();
    }

    function check(input, message) {
        input.setCustomValidity(message || '');
    }

    function setupCheckout(form) {
        var groups = form.querySelectorAll('.pay-fields');
        var number = form.elements.card_number;
        var expiry = form.elements.card_expiry;
        var holder = form.elements.card_holder;
        var cvv = form.elements.card_cvv;

        // Поля скрытого способа оплаты отключаются, чтобы не мешать проверке.
        function syncMethod() {
            var chosen = form.querySelector('input[name="method"]:checked').value;
            groups.forEach(function (group) {
                var active = group.dataset.method === chosen;
                group.hidden = !active;
                group.disabled = !active;
            });
        }
        form.addEventListener('change', function (event) {
            if (event.target.name === 'method') {
                syncMethod();
            }
        });
        syncMethod();

        number.addEventListener('input', function () {
            var digits = number.value.replace(/\D/g, '').slice(0, 19);
            number.value = digits.replace(/(.{4})/g, '$1 ').trim();
            check(number, luhnValid(digits) ? '' : 'Номер карты введён неверно');
        });

        expiry.addEventListener('input', function () {
            var digits = expiry.value.replace(/\D/g, '').slice(0, 4);
            expiry.value = digits.length > 2 ? digits.slice(0, 2) + '/' + digits.slice(2) : digits;
            check(expiry, expiryValid(expiry.value) ? '' : 'Укажите действующий срок в формате ММ/ГГ');
        });

        cvv.addEventListener('input', function () {
            cvv.value = cvv.value.replace(/\D/g, '').slice(0, 3);
        });

        holder.addEventListener('input', function () {
            holder.value = holder.value.toUpperCase();
        });
    }

    function setupNames(form) {
        var home = form.elements.home;
        var away = form.elements.away;

        function compare() {
            var same = home.value.trim().toLowerCase() === away.value.trim().toLowerCase();
            check(away, same ? 'Названия должны различаться' : '');
        }
        home.addEventListener('input', compare);
        away.addEventListener('input', compare);
        compare();
    }

    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            // Кнопка с formnovalidate («Убрать») обходит проверку.
            var skip = event.submitter && event.submitter.formNoValidate;
            if (!skip && !form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
            }
        });
    });

    var checkout = document.getElementById('checkout');
    if (checkout) {
        setupCheckout(checkout);
    }

    document.querySelectorAll('form.names').forEach(setupNames);
})();
