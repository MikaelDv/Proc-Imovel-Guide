const cpfInput = document.getElementById('cpf');

cpfInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});