# Pay for Success


## Online Randomizer

The online randomizer is to facilitate randomization of youth into treatment as usual or special services.  The randomizer is currently available for review and testing.  Once approved, the site will be moved to the University of Michigan's server and unique log-in credentials will be provided to the designated individuals.  This system has two separate sets of log-in credentials.  One set is for field user and the other is for the independent evaluators.

### Field user

The field user represents the designated individual who will be performing the random assignment.  If there are multiple field users, each person will have their own unique log-in credentials.  The following address and credentials are available for review and testing of the site as a `field user`.

http://ssw-datalab.org/trialrandomizer

+ User test account name:  testuser
+ User test account password:  testpass

When using the randomizer, the designated field user should ensure the youth has met all criteria and is approved to be randomized.  At the time of randomization, the field user must have the `Youth ID` and the `geographic site` for randomization. The field user should proceed with randomization if these conditions are not met.  The field user should log into the site with the provided credentials and then make the random assignment.  When the random assignment is made, the field user will receive a confirmation screen that can be printed.  The random assignment will be stored in a secure database, and the random assignment record will also be emailed to the field user and independent evaluator (and any other individuals who have been designated to receive a randomization record).

The following video provides a demonstration of the randomization process:

http://www.screencast.com/t/Obf9owNTXn


### Independent evaluators

The independent evaluators will maintain and provide oversight on the usage of the randomizer.  This level of permission gives access to the randomization records, in addition to the capability of changing the randomization ratio if needed.

http://ssw-datalab.org/trialrandomizer

+ Independent evaluator account name:  superuser
+ Independent evaluator password:  test

When logged in as an IE, you have administrative control over the study parameters. You can see all the subjects added by field users, and can view and edit Users, Groups, Sites, and Settings.

## Installation (Advanced)

The application is based on the [CakePHP 2](http://cakephp.org/) framework. In order to install the application, you should first install the most recent version of CakePHP 2.x - please refer to the [CakePHP 2 documentation](http://book.cakephp.org/2.0/en/installation.html) for instructions on doing this. If using Apache, you may need to enable and configure `mod_rewrite` according to [this documention](http://book.cakephp.org/2.0/en/installation/url-rewriting.html).

Once you have CakePHP installed and configured, import the contents of *database.sql* to your MySQL server. **This will insert several test users with fake emails and insecure credentials; do not leave them unaltered when deploying this application in a production environment.**

You may then replace the contents of the default CakePHP "app" directory with the contents of the "app" directory in this repository. You will need to modify the file permissions of the app/tmp directory and all subdirectories so that they are writeable by the web server user (e.g., "www-data" for many Apache2-based systems). You will also need to modify *app/Config/database.php* so that "DBUSER", "DBPASS", and "DATABASE" reflect the database to which you imported *database.sql*.

You also need to configure the hosting server's mail server and configure your webserver's `php.ini` appropriately. Once this is done, copy `app/Config/email.php.default` to `app/Config/email.php` and make the appropriate changes. If this is not configured correctly, users can add subjects, but will not receive any confirmation that the subject was added or to which group they were assigned.

## Architecture Notes (Advanced)

* Modifying the look-and-feel is standard CakePHP - you can change views by modifying the .ctp files contained underneath */app/View*, and you can modify the CSS styles in */app/webroot/css* as needed. Most headers and titles common to multiple pages can be modified by the superuser via the application's user interface on the Settings tab.
* Actual random assignment logic is implemented in */app/Controller/SubjectController.php*.


