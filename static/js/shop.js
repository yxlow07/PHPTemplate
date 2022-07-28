let lastSearched = "";
let container = $(".mainContent");

function reloadMixer() {
    // TODO: Does not respond after reloading...
    setTimeout(() => {
        let containerEl = document.querySelector('.mainContent');
        let mixer = mixitup(containerEl, {
            animation: {
                queueLimit: 100000,
                animateResizeContainer: false // required to prevent column algorithm bug
            }
        });
    }, 5000) // realised that this is to load the mixer not the books
}

function getBooks(container) {
    $.ajax({
        url: "./shop/books",
        success: function (result) {
            setTimeout(function () {
                container.empty();
                container.html(result);
            }, 0) // Changeable to make effect realistic
        },
        error: function (result) {
            container.html(result)
        }
    }, 1000);
}

function search(container, data) {
    $.ajax({
        url: "./shop/search",
        data: {"data": JSON.stringify(data)},
        method: "POST",
        success: (res) => {
            container.empty(); container.html(res); reloadMixer()
        },
        error: (res) => {
            container.empty(); container.html(res)
        }
    })
}

$("a.control").on('click', (event) => {
    event.preventDefault();
})

$("input#searchShop").on('keyup', function (){
    let val = $(this).val();

    if (val !== "" && val !== lastSearched) { // there is something to search & not non-input keys? like ctrl / shift...
        search(container, val.toString())
    }
    lastSearched = val;
})

$(document).ready(() => {
    getBooks(container);
    reloadMixer();
});