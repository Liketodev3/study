This file shares the details of the Yo!Coach version and change log.

Release Number: TV-2.14.0.20210519

Release Date: 2021-05-19

Updates:
    Task-83884-New Dashboard Design

--------------------------------------------------------

Release Number: TV-2.13.0.20210510

Release Date: 2021-05-10

Updates:

    - Disable Paygate and Paystack in restore DB.
    - Integrate Wiziq conferencing tool
    - URL rewrites
    - Open graph meta tags

---------------------------------------------------------

Release Number: TV-2.12.1.20210503

Release Date: 2021-05-03

Fixes:

    Bug-#053008 - Fix lesson interval on trial booking
    Bug-#053005 - update free-trial duration label

---------------------------------------------------------

Release Number: TV-2.12.0.20210426

Release Date: 2021-04-26

Updates:

    - Add 15 mins booking slot.
    - Give provision to admin to change trial lesson duration.

Fixes:

    Bug-#051847 - Fix checkout page ui for mobile resolution
    Bug-#052412 - Stop learner from reschduling lesson after a window passes
    Bug-#052672 - Fix media deletion on teach-langs section in admin
    Bug-#052674 - Fix issue on req withdrawl submission
    Bug-#052675 - Fix pagination and search issue in Top languages report

==============================================================================================================================
Installation steps:
        * Download the files and configure with your development/production environment.
        * Database schema files are placed under "{document_root}/database" directory.
        * Define DB configuration under {document_root}/public/settings.php.
        * You can get all the files mentioned in .gitignore file from git-ignored-files directory.
        * Rename -.htaccess file to .htaccess from {document_root} and {document_root}/public directory.
        * Upload Fatbit library Core folder under the {document_root}/library/.
        * Upload license file under the {document_root}/.
        * Update basic configuration as per your system requirements under {document_root}/conf directory.
        * Create a new "caching" directory under {document_root}/user-uploads.
        * Create a new "cache" directory under {document_root}/public.
        * Copy and rename on root user-upload-with-data or user-upload-without-data from git-ignored-files as per requirements and rename it with 'user-uploads'
        * After completion of installtion please hit the url: {domain-name}/dummy/create-procedures
            for e.g: https://teach.yo-coach.com/dummy/create-procedures

        * write permissions to
            {document_root}/user-uploads including all sub directories.
            {document_root}/user-uploads/caching.
            {document_root}/public/cache