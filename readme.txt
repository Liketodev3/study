This file shares the details of the Yo!Coach version and change log.

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

==============================================================================================================================
Release Number: RV-2.1.1

Release Date: 2021/02/19

Hotfixes:
    
    Bug-#049803- Fix transaction fee issue
    Bug-#049646- Fix currency conversion issue
    Bug-#049565- Fix cancel lesson submit button issue
    Bug-#049262- Fixed selection of Teacher availability at 11:30
    Bug-#049259- System should notifications when the student or teacher cancel the lesson
    Bug-#049283- Fix lesson plan attachment download issue
    Bug-#048531- Fix Label Class error on home page in case of analytics not found on google account
    Bug-#045944- Fix empty stripe token issue
    Bug-#047204- Third Party sign in button text editable
    Bug-#047271- Show correct reason for file not uploaded
    Bug:#046838- Make lesson page image manageable from admin
    Bug-#047296- Hide "this calendar is just for showing availability" on trial booking
    Bug-#047294- Timezone translations manageable from admin
    Bug-#047308- If teaching language not update, removed pop-up for update price
    Bug-#047908- Cancel Reschedule email template changes
    Bug-#048752- Fix issues with multi lingual functionality 
    Bug-#048675- Validate item per page field in settings
    Bug-#049348- Set homepage slide dimensions
    Task-73053- Fix root URL in top rated teachers list homepage

Updates:

    Suggestion-#047951: FAQ page with HTML content to add links and styling
    Task-73053- Set SSL ON for demo instance by default in restore DB
    Task-73053- Make banners button URL and text manageable
    Task-81879- Update demo content
    Task-78843- Upgrade zoom version - 1.8.5

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
