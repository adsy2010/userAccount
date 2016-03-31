The database files are designed to work in a REST API.

Status codes may be added depending on the complexity developed.

The API currently works with just 2 levels

/api/section/action/

In the future a 3rd level will be added which will control the object associated
for the API e.g. /api/section/action/object/

There are 2 main files currently in use.

db.php - This file controls the connection and the execution of data
responses.php - This file controls the responses provided by connecting to
mysql and adjusting or retrieving from the database.

The file index.php does contain a control class to manage the files required for
the database. A config.ini file must use the following format and should be placed
in the api directory (for now):

host="host"
username="username"
password="password"
database="database"
salt="typesomethingrandom"

This eventually will auto populate through an installer.