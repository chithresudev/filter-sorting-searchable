const popOvers = document.querySelectorAll("[data-pop-over]");
popOvers.forEach((popOver) => {
  const showPopOver = document.getElementById(
    popOver.dataset.popOver + "ContentShow"
  );
  // popOver.addEventListener("click", () => {
  var setUpOver = new bootstrap.Popover(popOver, {
    container: "body",
    placement: "bottom", // Default placement if not provided
    html: true,
    content: showPopOver,
  });
});

document.addEventListener("click", function (e) {
  $("[data-pop-over]").each(function () {
    // hide any open popovers when the anywhere else in the body is clicked
    if (
      !$(this).is(e.target) &&
      $(this).has(e.target).length === 0 &&
      $(".popover").has(e.target).length === 0
    ) {
      $(this).popover("hide");
    }
  });
});

var searchParam = new URLSearchParams(window.location.search);

function addFilter(el) {
  var { name, value } = el.previousElementSibling;
  searchParam.append(name, value);
  const queryString = new URLSearchParams(
    Object.fromEntries(new URLSearchParams(searchParam))
  );

  window.location.search = queryString;
}

function submitFilterForm(ee) {
  const formData = new FormData(ee);
  for (const pair of formData.entries()) {
    if (formData.get(pair[0])) {
      searchParam.append(pair[0], formData.getAll(pair[0]));
    }
  }
  const queryString = new URLSearchParams(
    Object.fromEntries(new URLSearchParams(searchParam))
  );
  window.location.search = queryString.toString();
}

//Removed binding params click
function filterRemove(removeElementParam) {
  let queryString;
  if (typeof removeElementParam != "undefined") {
    const removeParam = removeElementParam.parentNode.dataset.paramField;

    queryString = new URLSearchParams(
      Object.fromEntries(new URLSearchParams(searchParam))
    );

    [...queryString.entries()].forEach(() => {
      if (searchParam.get("sort_field") == removeParam) {
        queryString.delete("sort_direction");
        queryString.delete("sort_field");
      } else {
        queryString.delete(removeParam);
      }
    });
  } else {
    queryString = "";
  }
  window.location.search = queryString;
}

/*---------------------------------------------------
--------Table Column Switcher hide and show --------*/

let listColumnSwitcherAppend = document.querySelector(
  "[id=listColumnSwitcher_]"
);
let listColumnSwitcherTable = document.querySelector("table");

if (isNaN(listColumnSwitcherTable)) {
  let listColumnSwitcher = listColumnSwitcherTable.firstElementChild;

  const table = listColumnSwitcher.querySelector("tr");
  const appendId = listColumnSwitcherAppend;
  const index = 1;

  table.querySelectorAll("th").forEach((tableThColumn) => {
    const defaultValueData = tableThColumn.querySelector(
      "[data-table-column-switcher]"
    );
    const defaultValue = defaultValueData?.dataset?.tableColumnSwitcher;
    const labelName = tableThColumn.innerText;
    const labelId = labelName.replaceAll(" ", "_");

    if (labelName.length) {
      if (localStorage.getItem(`checkBoxTableId_${index}${labelId}`) == null) {
        checkedBoxValue =
          defaultValue == "default"
            ? "disabled checked"
            : defaultValue == true
            ? "checked"
            : "";
      } else {
        checkedBoxValue = localStorage.getItem(
          `checkBoxTableId_${index}${labelId}`
        );
      }

      let listColumnContent = `<div class="form-check">
        <input class="form-check-input" type="checkbox" ${checkedBoxValue} id="checkBoxTableId_${index}${labelId}">
            <label class="form-check-label" for="checkBoxTableId_${index}${labelId}">${labelName}
            </label>
        </div>`;

      appendId.insertAdjacentHTML("beforeend", listColumnContent);
    }

    let tableColumnSwitchersAction = document.getElementById(
      `checkBoxTableId_${index}${labelId}`
    );
    cellIndex = tableThColumn.cellIndex + 1;

    if (tableColumnSwitchersAction.checked) {
      $(
        "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
      ).show();
    } else {
      $(
        "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
      ).hide();
    }

    tableColumnSwitchersAction.addEventListener(
      "change",
      (tableColumnSwitchersActionValue) => {
        cellIndex = tableThColumn.cellIndex + 1;
        if (tableColumnSwitchersActionValue.target.checked == true) {
          localStorage.setItem(`checkBoxTableId_${index}${labelId}`, "checked");
          $(
            "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
          ).show();
        } else {
          localStorage.setItem(`checkBoxTableId_${index}${labelId}`, null);
          $(
            "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
          ).hide();
        }
      }
    );
  });
} else {
  listColumnSwitcherAppend.parentElement.parentElement.hidden = true;
}

/* Crear all data */
function myClearAll() {
  localStorage.clear();
  window.location.reload();
}
