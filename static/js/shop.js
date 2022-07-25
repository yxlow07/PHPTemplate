function getBooks(container) {
    $.ajax({
        url: "./shop/books",
        success: function (result) {
            setTimeout(function () {
                container.empty();
                container.html(result);
            }, 0)
        },
        error: function (result) {
            container.html(result)
        }
    }, 1000);
}

$("a.control").on('click', (event) => {
    event.preventDefault();
})

$(document).ready(() => {
    let container = $(".mainContent");
    // getBooks(container);

    setTimeout(() => {
        let containerEl = document.querySelector('.mainContent');

        let mixer = mixitup(containerEl, {
            animation: {
                animateResizeContainer: false // required to prevent column algorithm bug
            }
        });
    }, 2000)
});