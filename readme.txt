This file shares the details of the Yo!Coach version and change log.


Release Number: 
Release Date:

New Features:
. Task-79596 - Add  2Checkout payment gateway Payment gateway

Enhancements:
  
Bug Fixes:
    Bug-#044725: Fixed incorrect end timer
    Bug-#049602-Issue with speaking and proficiency level
    Bug-#047721: Trial lesson booking not working
    Bug-#050350: Need to add validation alert , bulk price should always be less than single lesson rate, other wise teacher will not display if bulk price of any lesson is greater than existing maximum single lesson rate of website teacher list.


----------------------------------------------------------

Release Number: TV-2.8.3.20210212

Release Date: 2021/02/12

Hotfixes:
    Task-81879-update labels, filters on tutor search and make lessonspace default
    Bug-#049803-fix transaction fee issue
    Bug-#049646-fix currency conversion issue

----------------------------------------------------------


Release Number: TV-2.8.2.20210211

Release Date: 2021/02/11

Hotfixes:
    Task-81879-update demo homepage content along with hotfixes
    Task-73053-fix banners button URL and text
    Task-73053-fix root URL in top rated teachers list homepage

----------------------------------------------------------

Release Number: TV-2.8.1.20210209

Release Date: 2021/02/09

Hotfixes:
    Bug-#049283-fix attach lesson plan issue
    Bug-#049565-fix cancel lesson submit button issue
------------------------------------------------------------

Release Number: TV-2.8.0.20210208

Release Date: 2021/02/08

Hotfixes:
    Bug-#049262: Fixed selection of Teacher availability at 11:30
    Bug-#049259-System should notifictions when the student or teacher cancel the lesson
    Task-78843-Upgrade zoom version - 1.8.5
    Bug-#047294-fix keyword search
    Bug-#048675-validate page size
    Bug-#049283-fix lesson plan attachment download
    Bug-#048531-fix label Class error on home page in case of analytics not found on google account

------------------------------------------------------------

Release Number: TV-2.7.12.20210206

Release Date: 2021/02/06

Hotfixes:
    Bug-#045944-fix empty stripe token issue
    Task-73053-set SSL ON for demo instance by default
    Bug-#047204-Google sign in button text editable
    Bug-#047271-Show correct reason for file not upload
    Bug:#046838: change lesson page image
    Bug-#047296-Hide "this calendar is just for showing availabilty" on trial booking
    Bug-#047294-Timezone translations manageable from admin
    Bug-#047308-If teaching language not update, removed popup for update price
    Bug-#047908: Cancel Reschedule email template changes
    Suggestion-#047951: FAQ page with html content to add links and styling
    Bug-#048752-Fix issues with multi lingual functionality 
    Bug-#48675-validate item per page field in settings
    Bug-#049348-set homepage slide dimensions

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
