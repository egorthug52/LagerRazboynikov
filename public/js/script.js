$(document).ready(() => {
  const diseaseDate = $("#disease_date");
  const confirmedDate = $("#confirmed_date");
  const cancellationDate = $("#cancellation_date");
  const form = $("#patientForm");

  $("#diagnosis").select2({
    placeholder: "Выберите диагноз",
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

  // Обработка полей имени
  ["first_name", "middle_name", "last_name"].forEach((field) => {
    $(`#${field}`).on("input", function () {
      let value = $(this)
        .val()
        .replace(/[^a-zA-Zа-яА-Я\s]/g, "");
      $(this).val(value.replace(/(^|\s)\S/g, (char) => char.toUpperCase()));
    });
  });

  // Обработка телефона
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

  form.on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    for (let pair of formData.entries()) {
      console.log(pair[0] + ": " + pair[1]);
    }

    const progressBar = $("#uploadProgress");
    const progress = progressBar.find(".progress-bar");
    const message = $("#uploadMessage");

    $.ajax({
      url: "./php/save.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      xhr: function () {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener(
          "progress",
          function (evt) {
            if (evt.lengthComputable) {
              var percentComplete = (evt.loaded / evt.total) * 100;
              progress.css("width", percentComplete + "%");
              progressBar.show();
            }
          },
          false
        );
        return xhr;
      },
      beforeSend: function () {
        progressBar.show();
        progress.css("width", "0%");
        message.text("Загрузка...");
      },
      success: function (response) {
        try {
          const res = JSON.parse(response);
          if (res.success) {
            message.text("Пациент успешно сохранен!");
            progress.css("width", "100%");
            setTimeout(() => (window.location.href = "index.php"), 2000);
          } else {
            message.text(res.error || "Ошибка при сохранении!");
          }
        } catch (e) {
          message.text("Ошибка обработки ответа: " + e.message);
        }
      },
      error: function (xhr) {
        message.text("Ошибка: " + (xhr.statusText || "неизвестная ошибка"));
      },
      complete: function () {
        setTimeout(() => {
          progressBar.hide();
          progress.css("width", "0%");
        }, 2000);
      },
    });
  });

  toggleFields();
});
