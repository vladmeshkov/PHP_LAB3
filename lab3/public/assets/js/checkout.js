// Показывает поля только выбранного способа оплаты.
// Без JavaScript форма остаётся рабочей: сервер читает поля по выбранному методу.
(function () {
    var form = document.getElementById('checkout');
    if (!form) {
        return;
    }

    var groups = form.querySelectorAll('.pay-fields');

    function sync() {
        var chosen = form.querySelector('input[name="method"]:checked').value;
        groups.forEach(function (group) {
            group.hidden = group.dataset.method !== chosen;
        });
    }

    form.addEventListener('change', sync);
    sync();
})();
