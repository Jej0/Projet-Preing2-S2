document.getElementById('accept_terms').addEventListener('change', function () {
    if (this.checked) {
        document.getElementById('contractForm').submit();
    }
});