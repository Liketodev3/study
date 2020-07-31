This file shares the details of YoCoach version and features.

Release Number: RV-2.0
Release Date: 2020/07/31

New Features:
        * Task-74784: 1-1 class
        * Task-73154: Group class
        * Task-75315: Google Calendar
        * Task-74126: Added stripe payment gateway

Enhancements: N/A
        * Task-75025: Ux changes for v2.0 release
        * Task-74969: Changes in the re-schedule flow
        * Teacher request log in the admin panel
        * Import/Export of labels
        * Refinement of re-schedule flow
        * Added separate section for order lessons in the admin panel
        * Add a cookie message on the product.Allow the user to accept the same.
        * Speed optimization of Find a teacher page
        * Changed the lesson re-scheduling flow for learners and teachers
        * Added the legends in the calendar regarding the booking slots
        * Upgraded the jquery version of the calendar
        * Upgraded the Comet Chat API version
  
Bug Fixes:
        * RTL design fixes
        * Issues with live chat code and GTM Code
        * Teacher re-submit application process
        * In case the admin cancels the orders, the refund is not happening
        * Admin > The start date and end date is incorrect in the case of 'Need to be Scheduled' lessons.
        * A free Trial lesson is being charged
        * Social login issues
        * Teacher listing page > Pagination is not working correctly
        * When the teacher submits his resume as an attachment, it is visible to admin
        * Issue Report fixes
        * Several another low/minor/medium bugs fixed

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