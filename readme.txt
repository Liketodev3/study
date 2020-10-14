This file shares the details of YoCoach version and features.

New Features:

    73053-update payment success message and remove extra space
    73053-set response headers from controller
    73053-to not reload page on (un)marking a teacher as favorite
    73053-fix fonts on front end
    73053-show pointer cursor on cupon code(checkout)
    73053-fix broken contact us link on payment failed page
    73053-free trial with 0 payment must not got to checkout page. No transaction enrty can happen with 0 amount.
    73053-add loader on book session and remove "redirecting in 3 seconds" message
    73053-fix webroot url usages

Enhancements:
    *Task-73053: Refinements in showing my lessons
    *Task-73053: Hide Request withdrawal from wallet for learner.
    *Task-73053: Font refinements
    *Task-73053: Code refinement for teacher profile url
  
Bug Fixes:


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