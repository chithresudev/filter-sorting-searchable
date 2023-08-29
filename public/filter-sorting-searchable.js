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
