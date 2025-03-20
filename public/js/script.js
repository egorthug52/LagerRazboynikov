$(document).ready(() => {
  const diseaseDate = $("#disease_date");
  const confirmedDate = $("#confirmed_date");
  const cancellationDate = $("#cancellation_date");
  const form = $("#patientForm");

  const select2Options = {
    placeholder: "Выберите значение",
    allowClear: true,
  };

  $("#diagnosis").select2({
    ...select2Options,
    allowClear: false,
    placeholder: "Выберите диагноз",
  });

  $(".user_region").select2({
    ...select2Options,
    allowClear: false,
    placeholder: "Выберите регион",
    width: "200px"
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

  $(document).ready(() => {
    $(".save-btn").on("click", function () {
      const $row = $(this).closest("tr");
      const userId = $row.data("user-id");
      const updatedData = {};

      $row.find('td[contenteditable="true"]').each(function () {
        const $cell = $(this);
        const field = $cell.data("field");
        let value = $cell.text().trim();

        if (field === "isAdmin") {
          value = value === "Администратор" ? 1 : 0;
        } else if (field === "superuser") {
          value = value === "Суперюзер" ? 1 : 0;
        } else if (field === "full_name") {
          const [last_name, first_name, middle_name] = value
            .split(" ")
            .filter(Boolean);
          updatedData["last_name"] = last_name || "";
          updatedData["first_name"] = first_name || "";
          updatedData["middle_name"] = middle_name || "";
        } else {
          updatedData[field] = value;
        }
      });

      $.ajax({
        url: "./php/update_user.php",
        method: "POST",
        data: {
          user_id: userId,
          ...updatedData,
        },
        success: function (response) {
          try {
            const result = JSON.parse(response);
            if (result.success) {
              alert("Данные успешно обновлены!");
            } else {
              alert("Ошибка: " + result.message);
            }
          } catch (e) {
            alert("Ошибка при обработке ответа сервера");
          }
        },
        error: function () {
          alert("Ошибка связи с сервером");
        },
      });
    });

    $('td[contenteditable="true"]')
      .on("focus", function () {
        $(this).css("background-color", "#fff3cd");
      })
      .on("blur", function () {
        $(this).css("background-color", "rgba(255, 255, 255, 0.7)");
      });
  });
});
