<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmació</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tancar"></button>
            </div>
            <div class="modal-body" id="confirmModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel·la</button>
                <button type="button" class="btn btn-dark" id="confirmModalBtn">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    var modalEl = document.getElementById('confirmModal');
    if (!modalEl || typeof bootstrap === 'undefined') {
        return;
    }

    var modal = new bootstrap.Modal(modalEl);
    var modalBody = document.getElementById('confirmModalBody');
    var btnConfirm = document.getElementById('confirmModalBtn');
    var formPendent = null;
    var selectQuantitat = null;

    document.querySelectorAll('form.js-confirm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formPendent = form;
            selectQuantitat = null;
            modalBody.innerHTML = '';

            var missatge = document.createElement('p');
            missatge.textContent = form.dataset.message || 'Estàs segur?';
            modalBody.appendChild(missatge);

            var max = parseInt(form.dataset.quantitatMax || '', 10);
            if (!isNaN(max) && max > 0) {
                var label = document.createElement('label');
                label.className = 'form-label';
                label.setAttribute('for', 'confirmModalQuantitat');
                label.textContent = 'Nombre de persones';

                selectQuantitat = document.createElement('select');
                selectQuantitat.className = 'form-select';
                selectQuantitat.id = 'confirmModalQuantitat';
                for (var q = 1; q <= max; q++) {
                    var opt = document.createElement('option');
                    opt.value = q;
                    opt.textContent = q;
                    selectQuantitat.appendChild(opt);
                }

                modalBody.appendChild(label);
                modalBody.appendChild(selectQuantitat);
            }

            modal.show();
        });
    });

    btnConfirm.addEventListener('click', function () {
        modal.hide();
        if (formPendent) {
            if (selectQuantitat) {
                var input = formPendent.querySelector('input[name="quantitat"]');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'quantitat';
                    formPendent.appendChild(input);
                }
                input.value = selectQuantitat.value;
            }
            formPendent.submit();
        }
    });
})();
</script>
