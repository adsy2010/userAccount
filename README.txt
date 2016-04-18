This set of code is designed as the base for any user login system for any site.

The database is designed in a REST API format. That is the complex stuff.

The site itself contains just the basics for a user login system.

Register
Login
Logout
Profile
Reset password*
Activate account*

Admin tools will be developed too

View all users*
Manage user*
Delete user*

Anything with a * has not had any development on it yet

The registration process will send an email to the registered address with a
randomly generated link. This link will run an activation.

The login process sets a single session running and allows the developer to decide
whether any information about the user is needed to be able to verify permissions
or determine any ongoing action relating to that user.

The logout simply kills the session. (the session table may be adjusted to kill
sessions automatically or provide a session close time, the alternative to a
cookie).

