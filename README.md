# A Blog App - School Project for CSE 341

TODO (Project):

Need to finish Tags Controller & Views.
Need to parse Error Messages, and create a DD Page or Kill Screen.
Need to test test test...

Post Display View needs some love.

Models are janky... could look into creating a simple ORM and singleton for the DB Class.

Missing App Service Container? QQ

User Dashboard... Directory, Edit, and Delete

Stretch Goals:
Optimize Authorization...
Add a function to isolate the first part of error strings (e.g. "Notice!:") to determine how it should be displayed. (e.g. "Notice" displays with class="Warning")

Post Project Ideas:

Install a robust ORM like Doctrine. Convert the App into a SPA (Vue?) with a simple API for interacting with Posts/Comments and Tags.

(Alternatively port into Lumen)

Future proofing: Fix "Autoload PSR-4 issue with Composer 2.0"

Normalize the Code:

Add Model and Controller base classes to contain common methods/properties for classes.
