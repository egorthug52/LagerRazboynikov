$(document).ready(function() {
    $('#phone_number').on('input', function() {
        let phone = $(this).val();
        if (!/^\d+$/.test(phone)) {
            alert('Номер телефона должен содержать только цифры!');
            $(this).val('');
        }
    });
});