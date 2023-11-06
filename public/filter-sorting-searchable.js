/*---------------------------------------------------
--------Filter and sorting --------*/

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

let searchParam = new URLSearchParams(window.location.search);

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
      if (searchParam.get("sort_field")) {
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

let tableColumnSwitchers = document.querySelectorAll(
  "[data-table-column-switcher]"
);
let listColumnSwitcher = document.getElementById("listColumnSwitcher");

tableColumnSwitchers.forEach((tableColumnSwitcher, index) => {
  let [condition, fieldName] =
    tableColumnSwitcher.dataset.tableColumnSwitcher.split("#");
  if (condition == true) {
    checkedBoxValue = localStorage.getItem(`defaultCheck${fieldName}`);
    let listColumnContent = `<div class="form-check">
    <input class="form-check-input" type="checkbox" ${checkedBoxValue}  id="defaultCheck${fieldName}">
        <label class="form-check-label" for="defaultCheck${fieldName}">${fieldName}
        </label>
    </div>`;

    listColumnSwitcher.insertAdjacentHTML("beforeend", listColumnContent);
    let tableColumnSwitchersAction = document.getElementById(
      `defaultCheck${fieldName}`
    );

    if (tableColumnSwitchersAction.checked) {
      cellIndex = tableColumnSwitcher.parentElement.cellIndex + 1;
      $(
        "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
      ).hide();
    }

    tableColumnSwitchersAction.addEventListener(
      "change",
      (tableColumnSwitchersActionValue) => {
        cellIndex = tableColumnSwitcher.parentElement.cellIndex + 1;
        if (tableColumnSwitchersActionValue.target.checked == true) {
          localStorage.setItem(`defaultCheck${fieldName}`, "checked");
          $(
            "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
          ).hide();
        } else {
          localStorage.setItem(`defaultCheck${fieldName}`, null);
          $(
            "th:nth-child(" + cellIndex + "), td:nth-child(" + cellIndex + ")"
          ).show();
        }
      }
    );
  }
});

/* Crear all data */
function myClearAll() {
  localStorage.clear();
  window.location.reload();
}
