$(document).ready(() => {
  const diseaseDate = $("#disease_date");
  const confirmedDate = $("#confirmed_date");
  const cancellationDate = $("#cancellation_date");
  const form = $("#patientForm");

  $("#diagnosis").select2({
    placeholder: "Выберите диагноз",
    allowClear: true,
  });

  $("#user_region").select2({
    placeholder: "Выберите регион",
    allowClear: true,
  });

  const toggleFields = () => {
    const isConfirmed = !!confirmedDate.val();
    const isCancelled = !!cancellationDate.val();

    confirmedDate
      .prop("disabled", isCancelled)
      .attr("required", !isConfirmed && !isCancelled);
    cancellationDate
      .prop("disabled", isConfirmed)
      .attr("required", !isConfirmed && !isCancelled);
  };

  const validateDates = () => {
    const diseaseTime = new Date(diseaseDate.val()).getTime();
    ["confirmed", "cancellation"].forEach((type) => {
      const dateField = $(`#${type}_date`);
      if (
        dateField.val() &&
        new Date(dateField.val()).getTime() < diseaseTime
      ) {
        alert(
          `Дата ${
            type === "confirmed" ? "подтверждения" : "отмены"
          } не может быть раньше даты установления диагноза!`
        );
        dateField.val("");
        toggleFields();
      }
    });
  };

  [confirmedDate, cancellationDate].forEach((field) => {
    field.on("change", () => {
      validateDates();
      toggleFields();
    });
  });

  ["first_name", "middle_name", "last_name"].forEach((field) => {
    $(`#${field}`).on("input", function () {
      let value = $(this)
        .val()
        .replace(/[^a-zA-Zа-яА-Я\s]/g, "");
      $(this).val(value.replace(/(^|\s)\S/g, (char) => char.toUpperCase()));
    });
  });

  const phoneField = $("#phone_number");
  phoneField.on("input", function () {
    let value = $(this).val();
    if (!value.startsWith("+7")) value = "+7" + value.slice(2);
    value = value.slice(0, 2) + value.slice(2).replace(/\D/g, "").slice(0, 10);
    $(this).val(value);
  });

  phoneField.on("keydown", (e) => {
    if (e.target.selectionStart < 2) e.preventDefault();
  });

  toggleFields();
});
