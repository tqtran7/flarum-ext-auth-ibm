
# Description

This is a flarum extension that allow users to sso into the forum by making use of information stored in the cookie from a previous session. This is useful for websites that want to enable sso to flarum from their primary (such as wordpress). If the cookie is encrypted, you must provide the key and salt.

# Setup Instructions

1. Use composer to install: `composer require tqtran7/flarum-ext-auth-ibm`
2. Add sso.ini file to src directory.
3. Add KEY and SALT like below:
```
    key    = "SECRET"
    salt   = "SECRET"
```
4. Enable the extension using flarum admin page
