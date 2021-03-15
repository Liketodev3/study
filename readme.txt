This file shares the details of the Yo!Coach version and change log.

Release Number:
Release Date:

Bugs
    Bug-#050724 - Admin >> need to remove.
    Bug-#050538  - #050538 - In Group class >>Why user can not set his group class price more than highest lesson package rate in website.
    Bug-#050556 - If user did not join a lesson even session start time is crossed. but time left view is showing, which is wrong.
    Bug-#050350: Need to add validation alert , bulk price should always be less than single lesson rate, other wise teacher will not display if bulk price of any lesson is greater than existing maximum single lesson rate of website teacher list.
    Bug-#050730 - Front end >> teacher sign up >> resume >> upload invalid resume format >> loader keep on revolving >> after giving error message.
    Bug-#050754 - Front end >> teacher >> teacher setting >> when don't change any language >> clik on save button then also price become clear >> and teacher not able to know. 
    Bug-#050820 - Teacher >> settings >> Skills >> same language is coming multiple time.
    Bug-#050759 - Admin >> Dashboard >> total revenue from lesson >> view details >> need to correct ui
    Bug-#050761 - Admin >> Dashboard >> total revenue from lesson >>view schedule >> need to correct ui 
    Bug-#047550 - Listing Teacher : Teaches Language information
    Bug-#050799 - Admin > cms >> lesson package management >> if disable free trial >> it then also show at front end at teacher profile.
    Bug-#050841 - Teacher >> weekly schedule coming as twice in single row.
    Bug-#050923 - Why a learner can join a lesson and group class with almost same time with different teachers. 
    Bug-#051112 - Currency/Languages scroller is not available if User added 100 currencies/languages they are not displaying in the right manner on the website 
    Bug-#051078 - The transaction limit should be set to 1 cr at the time when user adds money to the wallet. 
    Bug-#050853 - User >> if teacher has mutiple langugae >> at user side select another language >> below radion button donot remain selected and able to do payment of Zero order. 
    Bug-#051034 - Find a Teacher and Teacher page > Add some space
==============================================================================================================================
Release Number: TV-2.10.0.20210304
Release Date: 2021-03-04

Updates:
    *Task-80349: Update Stripe to support SCA( Strong Customer Authentication) task_80349_stripe_intent
    *Task-80518: Multiple Booking Slots
    *Task-81798: Commission report
    *Task-79595-Admin can switch PWA ON or OFF
    *Task-82683- Changes on Dashboard> Total Revenue from lessons

Bugs:
    Bug-#050554 - In admin and front end>> some where did not mention about the preferred image required for upload.
    Bug-#050141 - When teacher set his profile feature BOOKING BEFOIRE is 12 hours before. then learner side its showing wrong alert.
    Bug-#050150 - Spacing between the social media buttons is not correct.
    Bug-#044719 - When user purchase any lesson then success message is showing> but link button is not highlighted properly
    Bug-#045486 - In teacher detail screen >> rating view >> text is looking too bold.
    Bug-#049899 - Email Notification is missing> When teacher is placing wallet withdrawal request admin do not receive any notification through email.
    Bug-#050557 - Fix meta tag search
    Bug-#050577 - Change in search teacher query condition

---------------------------------------------------------

Release Number: TV-2.9.0.20210223
Release Date: 2021-02-23

New Features:
    . Task-79596 - Integrate 2Checkout payment gateway
    . Task-79596 - Integrate Paygate payment gateway
    . Task-81706 - Integrate Paystack Payment gateway
    . Task-79595 - PWA

Enhancements:
    . Task-82203: Image for allowed payment gateways/cards on the platform
    . Task-81501: Refine and refactor Meta Tags Management
    . Task-80174: User images optimization
    . Task-82382: Auto Language selection
    . Task-82103: Teachers search query optimization
   
Bug Fixes:
    Bug-#044725: Fixed incorrect end timer
    Bug-#049602: Issue with speaking and proficiency level
    Bug-#047721: Trial lesson booking not working
    Bug-#049783: Fix Statistics graphical representation
    Bug-#044726: To not show join lesson button on page reload if already joined
    Bug-#044692: Fix Lesson status issue in orders in admin panel
    Bug-#045497: Make report issue options user type specific and fix admin privileges
    Bug-#049646: Refine currency handling

----------------------------------------------------------

Release Number: TV-2.8.3.20210212

Release Date: 2021/02/12

Hotfixes:
    Task-81879-update labels, filters on tutor search and make lessonspace default
    Bug-#049803-fix transaction fee issue
    Bug-#049646-fix currency conversion issue

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
