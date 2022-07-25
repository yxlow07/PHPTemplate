<div id="mainWrapper">
    <div id="content">
        <section class="sidebar">
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

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Launch static backdrop modal
        </button>

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">The Dragon's Familiar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <b>Series name:</b> <i>Seirei Gensouki</i><br>
                        <b>Volume:</b> <i>2</i><br>
                        <b>Author:</b> <i>Kitayama Yuri</i><br>
                        <b>Publisher:</b> <i>HJ Bunko by Japan</i><br>
                        <b>Price:</b> <i>781yen</i><br><br>
                        <b>Synopsis:</b> <br/>
                        <code>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium atque eos iste saepe
                            voluptatem! Animi consequuntur dicta, dolorem, earum error esse ex iste possimus, quaerat
                            quas quo temporibus totam veniam?</code>
                        <br/><br>
                        <b>Illustrations: </b>
                        <div class="horizontal-center">
                            <img src="{home}/static/images/main/load.gif" alt="Loading..." style="width: 80%"><br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
\app\views\Widgets::js_script("{home}/static/js/shop.js");
?>