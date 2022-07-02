<?php

use app\views\Widgets;

Widgets::roundCenterProfilePicHover(['session' => 'user_info||profile_img'], "Profile Picture");
Widgets::rowWithTwoColumns("Username", ['session' => 'username']);
Widgets::rowWithTwoColumns("Email", ['session' => 'email']);
Widgets::rowWithTwoColumns("Name", ['session' => 'user_info||name']);
Widgets::rowWithTwoColumns("Bio", ['session' => 'user_info||bio']);
Widgets::rowWithTwoColumns("Date created", ['session' => 'date_created']);
Widgets::centerDivLinkBtn("{home}/edit_profile", "Edit profile");
Widgets::js_script("{home}/static/js/profile.js");
?>

<!-- Modal -->
<div class="modal fade" id="pfp" tabindex="-1" aria-labelledby="uploadProfilePicture" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProfilePictureTitle">Update your Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center ">
                <input
                        type="file"
                        name="pfp"
                        class="form-control"
                        id="updatePfp"
                        onchange="savePhoto(this)"
                        accept="image/jpeg, image/png, image/gif, image/bmp, image/tiff, image/svg, image/webp"
                >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>