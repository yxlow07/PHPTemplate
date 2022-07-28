<div class="search-bar">
    <input type="text" class="form-control" placeholder="Search here...." id="searchShop">
</div>
<div id="mainWrapper">
    <div id="content">
        <section class="sidebar">
            <div id="menubar">
                <nav class="menu">
                    <ul>
                        <li><a href="" class="control" data-filter=".fantasy">fantasy</a></li>
                        <li><a href="" class="control" data-filter=".sci-fi">sci-fi</a></li>
                        <li><a href="" class="control" data-filter=".romance">romance</a></li>
                        <li><a href="" class="control" data-filter=".thriller">thriller</a></li>
                        <li><a href="" class="control" data-filter=".horror">horror</a></li>
                        <li><a href="" class="control" data-filter=".historical">historical</a></li>
                        <li><a href="" class="control" data-filter=".mystery">mystery</a></li>
                        <li><a href="" class="control" data-filter=".dystoaian">dystopian</a></li>
                        <li><a href="" class="control" data-filter=".new-adult">new adult</a></li>
                        <li><a href="" class="control" data-filter=".non-fiction">non-fiction</a></li>
                        <li><a href="" class="control" data-filter=".isekai">isekai</a></li>
                        <li><a href="" class="control" data-filter=".supernatural">supernatural</a></li>
                        <li><a href="" class="control" data-filter=".drama">drama</a></li>
                        <li><a href="" class="control" data-filter=".slice-of-life">slice of life</a></li>
                        <li><a href="" class="control" data-filter=".shoujo">shoujo</a></li>
                        <li><a href="" class="control" data-filter=".action">action</a></li>
                        <li><a href="" class="control" data-filter=".ecchi">ecchi</a></li>
                        <li><a href="" class="control" data-filter=".adventure">adventure</a></li>
                    </ul>
                </nav>
            </div>
        </section>
        <section class="mainContent">
            <img src="{home}/static/images/main/load.gif" alt="Loading..." style="width: 80%">
        </section>
    </div>
</div>

<?php
\app\views\Widgets::js_script("{home}/static/js/shop.js", true);
?>