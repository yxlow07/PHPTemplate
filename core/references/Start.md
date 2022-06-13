## The mechanics
****
### Introduction
I'm sure a lot of people are interested in how things work behind the scenes. However, as many frameworks/open sourced
libraries do, it is sometimes a big hassle to be doing such things and it is best to protect the project's secrets
_(btw this is what I thought and is not necessarily correct)_ Enough with the formalities, I'm going to start at the basic
root of the project and work ourselves deeper and deeper. This is also broke into different sections to make reading all
the boring stuff much more organised.

### The root of all, `.htaccess` file
The `.htaccess` is actually pretty simple and I made it with no prior knowledge in apache and how things work although I
did some research when things went horribly south. 

The main part of this file is the 
```apacheconf
RewriteCond %{REQUEST_FILENAME} -f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule !.(js|css|ico|gif|jpg|png)$ router.php [L]
```

This checks if the requested url is not a directory and is a file, it proceeds to the `RewriteRule` and checks if the 
ending of the file is a specific extension. If it is, the file is served without going through to the PHP server while if
is, it is passed to the router for further route recognition.

That's basically what this file is meant to do apart from some error documents redirection

_The rest is still in alpha testing and not complete so please stay tuned for that_