<?php

use app\views\Widgets;

Widgets::startForm("", "post");
Widgets::roundCenterProfilePic(['session' => 'user_info||profile_img'], "Profile Picture");
Widgets::rowWithTwoColumnsEditable("Username", ['session' => 'username']);
Widgets::rowWithTwoColumnsEditable("Email", ['session' => 'email']);
Widgets::rowWithTwoColumnsEditable("Name", ['session' => 'user_info||name']);
Widgets::rowWithTwoColumnsEditable("Bio", ['session' => 'user_info||bio']);
Widgets::submitBtn();
Widgets::endForm();
Widgets::js_script("{home}/static/js/profile.js");
