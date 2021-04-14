This file shares the details of the Yo!Coach version and change log.

Release Number: TV-2.11.9.20210414

Release Date: 2021-04-14

    .#052314- Remove delete lesson package option from admin panel
    .#052296- Fix conflict of preferance form

------------------------------------------------------------------------

Release Number: RV-2.2

Release Date: 2021-04-12

New Features:

    *79596: Integrate 2Checkout payment gateway
    *79596: Integrate Paygate payment gateway
    *81706: Integrate Paystack Payment gateway
    *81802: Lesson reschedule and cancellation report
    *80518: Multiple Booking Slots
    *81798: Commission report
    *79595: Basic PWA
    *80349: Update Stripe to support SCA( Strong Customer Authentication)

Updates:

    *82683: Refine 'Total Revenue from lessons' on dashboard
    *82203: Image for allowed payment gateways/cards on the platform
    *81501: Refine and refactor Meta Tags Management
    *80174: Images optimization
    *82382: Auto Language selection
    *82103: Teachers search query optimization
    *78843: Upgrade zoom version - 1.9.1
    *73053: Fix banners button URL and text and some bug fixes

Bugs:

    - #051199: Mobile resolution friendly email templates
    - #045564: Saturday availability not showing correctly
    - #048965: Emails with capital letters, can't start lesson
    - #049602: Issue with speaking and proficiency level
    - #050886: When user search Availability for all teachers for particular days then its not working accordingly.
    - #051042: If teacher set general Availability and not  save current week's weekly Availability then system not allow to book a lesson on that time
    - #044694: "Find a teacher> subjects in chronological order in the subjects dropdown If admin side you will reorder the subjects it is not showing in the same order at frontend"
    - #049735: Change profile picture - on mobile version> Profile Picture changer - unable to zoom in and out on the mobile version. The picture loads, and you can zoom out. But once zoomed out all the way you can no longer move or adjust zoom in the image
    - #050890: Once User registered, then No loader is showing during waiting or delay time.
    - #050953: Apple touch Icons are not working (404), after updated from admin panel
    - #051112: Currency/Languages scroller is not available if User added 100  currencies/languages  they are not displaying in the right manner  on the website
    - #049899: Email Notification is missing> When teacher is placing wallet withdrawal request admin does not receive any notification through email.
    - #049783: When user multiple click on statistics button then graphical representation has changed
    - #050923: Change week start and end date timezone

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