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

    document.querySelectorAll('form.js-confirm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formPendent = form;
            modalBody.textContent = form.dataset.message || 'Estàs segur?';
            modal.show();
        });
    });

    btnConfirm.addEventListener('click', function () {
        modal.hide();
        if (formPendent) {
            formPendent.submit();
        }
    });
})();
</script>
