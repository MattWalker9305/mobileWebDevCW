document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('transactionModal');
    var modal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.transaction-box').forEach(function (box) {
        box.addEventListener('click', function () {
            document.getElementById('modal-id').value       = box.dataset.id;
            document.getElementById('modal-name').value     = box.dataset.name;
            document.getElementById('modal-date').value     = box.dataset.date;
            document.getElementById('modal-amount').value   = box.dataset.amount;
            document.getElementById('modal-notes').value    = box.dataset.notes;
            document.getElementById('modal-category').value = box.dataset.category;
            document.getElementById('modal-currency').value = box.dataset.currency;
            document.getElementById('modal-type').value     = box.dataset.type;

            modal.show();
        });
    });
});

function saveTransaction() {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'update_transaction.php';

    var fields = {
        action:     'edit',
        id:         document.getElementById('modal-id').value,
        myname:     document.getElementById('modal-name').value,
        mydate:     document.getElementById('modal-date').value,
        myamount:   document.getElementById('modal-amount').value,
        mynotes:    document.getElementById('modal-notes').value,
        mycategory: document.getElementById('modal-category').value,
        mytype:     document.getElementById('modal-type').value,
        mycurrency: document.getElementById('modal-currency').value
    };

    for (var key in fields) {
        var input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = key;
        input.value = fields[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

function deleteTransaction() {
    if (!confirm('Are you sure you want to delete this transaction?')) return;

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'update_transaction.php';

    var fields = { action: 'delete', id: document.getElementById('modal-id').value };

    for (var key in fields) {
        var input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = key;
        input.value = fields[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}
