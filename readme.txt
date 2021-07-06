This file shares the details of the Yo!Coach version and change log.

Release Number: RV-2.3.1
Release Date: 2021-07-06

System requirements:

    PHP version upgrade to 7.4
    MySQL version upgrade to 5.7.8

Updates/Enhancements:

    task-86405 Footer social link alignment
    task-86405 Language & Currency dropdown on CMS pages
    task-86405 Tutor profile page in Arabic view
    task-86405 Filter-tag spacing in Arabic view
    task-86405 Listing card border in Arabic view
    task-86405 Listing card direction in Arabic view
    task-86405 Detail page book-now button in Arabic view
    task-86405 Registration form overlapping in Arabic view
    task-86405 Favorite teacher alignment
    task-87021 Teacher reviews listing updated
    task-87021 General availability booking issue
    task-87021 Group classes can be deleted by the admin
    task-87021 How it work button issue in apply to teach page
    task-87021 Week start & end date in the Arabic view on calendar
    task-87021 Full calendar local language updates
    task-87022 Change in setup issue action method conditions
    task-87022 Rescheduled action in the report issue module
    task-87259 Added SVG logo for demo URL and restricted to change on demo

Bug Fixes:    
    
    #055579
    #055485
    #055484

-------------------------------------------------------------------

Release Number: RV-2.3
Release Date: 2021-06-25

System requirements:

    PHP version upgrade to 7.4
    MySQL version upgrade to 5.7.8 or above (JSON support required)

Updates/Enhancements:
    
    - Refactored URL rewrites
    - Refactored meta tags
    - New Design
        - Dashboard
        - Homepage
        - Find a tutor page
        - Tutor profile page
        - Checkout Pages
        - Contact us
        - Group classes
        - About us
        - Apply to Teach
    - Refactored Report an issue
    - Refactored Apply to Teach
    - Multiple pricing slabs 
    - Admin can change user's password
    - Admin can edit robots.txt
    - Support to php7.4
    - Delete User Personal Details under GDPR

Fixes:

    The group class filter is working fine on the group classes page
    Various fixes in general and weekly availability

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

