<div id="mainWrapper">
    <div id="content">
        <section class="sidebar">
            <!-- This adds a sidebar with 1 searchbox,2 menusets, each with 4 links -->
            <div id="menubar">
                <nav class="menu">
                    <ul>
                        <li><a href="" class="control" data-filter=".fantasy">FANTASY</a></li>
                        <li><a href="" class="control" data-filter=".sci-fi">SCI-FI</a></li>
                        <li><a href="" class="control" data-filter=".romance">ROMANCE</a></li>
                        <li><a href="" class="control" data-filter=".thriller">THRILLER</a></li>
                        <li><a href="" class="control" data-filter=".horror">HORROR</a></li>
                        <li><a href="" class="control" data-filter=".historical">HISTORICAL</a></li>
                        <li><a href="" class="control" data-filter=".mystery">MYSTERY</a></li>
                        <li><a href="" class="control" data-filter=".dystoaian">DYSTOPIAN</a></li>
                        <li><a href="" class="control" data-filter=".new-adult">NEW ADULT</a></li>
                        <li><a href="" class="control" data-filter=".non-fiction">NON-FICTION</a></li>
                        <li><a href="" class="control" data-filter=".isekai">ISEKAI</a></li>
                        <li><a href="" class="control" data-filter=".supernatural">SUPERNATURAL</a></li>
                        <li><a href="" class="control" data-filter=".drama">DRAMA</a></li>
                        <li><a href="" class="control" data-filter=".slice-of-life">SLICE OF LIFE</a></li>
                        <li><a href="" class="control" data-filter=".shoujo">SHOUJO</a></li>
                        <li><a href="" class="control" data-filter=".action">ACTION</a></li>
                        <li><a href="" class="control" data-filter=".ecchi">ECCHI</a></li>
                    </ul>
                </nav>
            </div>
        </section>
        <section class="mainContent">
            <img src="{home}/static/images/main/load.gif" alt="Loading..." style="width: 80%">
        </section>
    </div>
</div>

<script>
    $("a.control").on('click', (event) => {
        event.preventDefault();
    })

    $(document).ready(() => {
        let container = $(".mainContent");

        $.ajax({
            url: "./shop/books",
            success: function (result) {
                setTimeout(function () {
                    container.empty();
                    container.html(result);
                }, 1000)
            },
            error: function (result) {
                container.html(result)
            }
        });

        setTimeout(() => {
            let containerEl = document.querySelector('.mainContent');

            let mixer = mixitup(containerEl, {
                animation: {
                    animateResizeContainer: false // required to prevent column algorithm bug
                }
            });
        }, 2000)
    });
</script>