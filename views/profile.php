<?php

use app\views\Widgets;

Widgets::roundCenterImg($_SESSION['user_info']['profile_img'], "Profile Picture");
Widgets::rowWithTwoColumns("Username", ['session' => 'username']);
Widgets::rowWithTwoColumns("Email", ['session' => 'email']);
Widgets::rowWithTwoColumns("Date created", ['session' => 'date_created']);
Widgets::rowWithTwoColumns("Bio", ['session' => 'user_info||bio']);
