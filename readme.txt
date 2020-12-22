This file shares the details of YoCoach version and features.

Hotfixes
        * Bug-045964: Show only relevant language for class
        * Bug-046368: Update button sync with google calendar
        * Bug-043080: show group class & trial lesson on google calendar
        * Bug-045944: fix empty stripe token issue
        * Bug-045420: fix unicode issue in emails and blog list


Release Number: TV-2.6.20201120
Release Date: 2020/11/20

Fixes and updates:
        * Bug-045421: fix lang labels search in admin panel and add labels in orders search form. fix unicode chars issue.
        * Bug-045400: to not list gift card in admin panel if not checkedout
        * Bug-045427: fix the speak lanuage issue
        * Bug-045434: remove Warnings on add/edit interface of Manage lang section in admin
        * Bug-044575: remove end lesson popups on rescheduled lesson
        * Bug-044573: email admin on teacher request
        * Bug-044552: admin can set blog image


==============================================================================================================================
Installation steps:
        * Download the files and configured with your development/production environment.
        * Database schema files have been placed under "{document_root}/database" directory.
        * Define DB configuration under {document_root}/public/settings.php.
        * You can get all the files mentioned in .gitignore file from git-ignored-files directory.
        * Renamed -.htaccess file to .htaccess from {document_root} and {document_root}/public directory.
        * Upload Fatbit library Core folder under the {document_root}/library/.
        * Upload license files under the {document_root}/.
        * Update basic configuration as per your system requirements under {document_root}/conf directory.
        * Create a new "caching" directory under {document_root}/user-uploads.
        * Create a new "cache" directory under {document_root}/public.
        * Copy and rename on root user-upload-with-data or user-upload-without-data from git-ignored-files as per requirements
        * Update basic configuration as per your system requirements under {document root}/conf directory.
        * After completion of installtion please hit the url:  {domain-name}/dummy/create-procedures
            for e.g: https://demo.yo-coach.com/dummy/create-procedures

        * write permissions to
            {document_root}/user-uploads including all sub directories.
            {document_root}/user-uploads/caching.
            {document_root}/public/cache