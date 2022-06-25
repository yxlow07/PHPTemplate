<?php

use app\views\Widgets;

Widgets::roundCenterProfilePic(['session' => 'user_info||profile_img'], "Profile Picture");
Widgets::rowWithTwoColumns("Username", ['session' => 'username']);
Widgets::rowWithTwoColumns("Email", ['session' => 'email']);
Widgets::rowWithTwoColumns("Name", ['session' => 'user_info||name']);
Widgets::rowWithTwoColumns("Bio", ['session' => 'user_info||bio']);
Widgets::rowWithTwoColumns("Date created", ['session' => 'date_created']);
Widgets::centerDivLinkBtn("{home}/edit_profile", "Edit profile");
Widgets::js_script("{home}/static/js/profile.js");