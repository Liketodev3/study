This file shares the details of the Yo!Coach version and change log.

Release Number: TV-2.13.0.20210510

Release Date: 2021-05-10

Updates:

    - Disable Paygate and Paystack in restore DB.
    - Integrate Wiziq conferencing tool
    - URL rewrites
    - Open graph meta tags

-----------------------------------

Release Number: TV-2.8.6.20210507

Release Date: 2021-05-07

Bugs:
    Bug-#053087 - Fix availability issue of full week
    Bug-#052900 - Only show teaching languages, bound with group classes list, in the dropdown.
                - Do not show canceled group classes
                - Only list upcoming and ongoing classes
                - Teacher can not add group class with inactive teach lang

----------------------------------------

Release Number: TV-2.12.1.20210503

Release Date: 2021-05-03

Fixes:

    Bug-#053008 - Fix lesson interval on trial booking
    Bug-#053005 - update free-trial duration label

------------------------------------------------

Release Number: TV-2.8.5.20210421
Release Date: 2021-04-21

Bugs:
    Bug-#050724 - Admin >> Remove Order Status dropdowns from General Settings.
    Bug-#050538 - In Group class >>Why user can not set his group class price more than highest lesson package rate in website.
    Bug-#050556 - If user did not join a lesson even session start time is crossed. but time left view is showing, which is wrong.
    Bug-#050350 - List teacher even if bulk price is greater than maximum single lesson rate.
    Bug-#051828 - Fix teach-lang multilingual issue and remove limit from records.
    Bug-#052412 - Stop learner from reschduling lesson after a window passes.
    Bug-#052672 - Fix media deletion on teach-langs section in admin
    Bug-#052674 - Fix issue on req withdrawl submission
    Bug-#052675 - Fix pagination and search issue in Top languages report

---------------------------------------------------------------------

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