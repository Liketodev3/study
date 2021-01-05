This file shares the details of the Yo!Coach version and change log.

Updates:
    *Task-80349: Update Stripe to support SCA( Strong Customer Authentication)
    *Task-78843: Upgrade zoom to v1.8.3
    *Task-79596: Paygate Payment gateway integration
    *Task-79596: 2checkout Payment gateway integration

Bug Fixes:    
    * Bug-#045564-Fixed saturday availability issue
    * Bug-#047296-Hide "this calendar is just for showing availabilty" on trial booking
    * Bug-#047271-Show correct reason for file not upload
    * Bug-#047294-Timezone translations manageable from admin
    
Hotfixes:
    Bug-#045944-fix empty stripe token issue
    Task-73053-set SSL ON for demo instance by default
    * Bug-045964: Show only relevant language for class
    * Bug-046368: Update button sync with google calendar
    * Bug-043080: show group class & trial lesson on google calendar
    * Bug-045944: fix empty stripe token issue
    * Bug-045420: fix unicode issue in emails and blog list

==============================================================================================================================
Installation steps:
        * Download the files and configure with your development/production environment.
        * Database schema files are placed under "{document_root}/database" directory.
        * Define DB configuration under {document_root}/public/settings.php.
        * You can get all the files mentioned in .gitignore file from git-ignored-files directory.
        * Renamed -.htaccess file to .htaccess from {document_root} and {document_root}/public directory.
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
