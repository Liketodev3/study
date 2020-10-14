This file shares the details of YoCoach version and features.

New Features:

Enhancements:

    *Task-73053-show all learners for group class. Change sections placements

Bug Fixes:
    *Bug-#043538: Home Page Slider > On changing the home page slider, it is not being updated
    *Bug-#043486: Updated images path for about us content block
    *Bug-#043739: Fixed image rotation issue
    *Bug-#043738: Allow all characters for first name and last name
    *Bug-#043562: Teacher profile language sort
    *Bug-#043357: Apply to teach > The language values should be displayed in alphabetical order
    *Bug-#043659: TimeZone > Change the time zone drop down values
    *Bug-#043851: Fixed css/js issue for ipad black screen
    *Bug-#043910: Fixed lessons Count on Teacher Dashboard
    *Bug-#043898: Updated group class filter title
    *Bug-#043847: Group class booking emails
    *Bug-#043801: Hide from footer if location, phone, email is empty
    *Bug-#043773: Hide success msg before page reload, when learner report issue
    *Bug-#043899: Added ongoing filter, removed status filter and updated no records label
    *Bug-#043904: Reload faviourite listing when clicked on unfavourite
    *Bug-#043882: Added loader in Gift card search
    *Bug-#043794: Fixed price filter issue
    *Bug-#043878: Multiple withdrawl requests
    *Bug-#043790: fix contact us page links
    *Bug-#043772: Fix lesson end popup.
    *Bug-#043926: Sort My Teachers and the teaching langs.
    *Bug-#043894: fix filters on fav teachers page
    *Bug-#043792: Error handling on posting a bigger image than allowed size.
    *Bug-#043915: display report issue link for expired lessons.
    *Bug-#043876: Cancelled order/lesson not allowed to change status
    *Bug-#043815: send verification link on reset password option too
    *Bug-#044114: remove category type from navigation dropdopwn
    *Bug-#043930: Fixed issue for change order status by admin, not reflect on statistics
    *Bug-#043896: Ui issues in group class search view
    *Bug-#044126: sort langs on home page on the basis of teachers count + disaplay order


--------------------------------------
Release Version: TV-2.1.1.20200925
Release Date: 2020/09/25

New Features:
    *Task-77527: Lesson Space Integration
    
Enhancements:
    *Task-73053: update payment success message and remove extra space
    *Task-73053: set response headers from controller
    *Task-73053: to not reload page on (un)marking a teacher as favorite
    *Task-73053: fix fonts on front end
    *Task-73053: show pointer cursor on cupon code(checkout)
    *Task-73053: fix broken contact us link on payment failed page
    *Task-73053: free trial with 0 payment must not got to checkout page. No transaction enrty can happen with 0 amount.
    *Task-73053: add loader on book session and remove "redirecting in 3 seconds" message
    *Task-73053: fix webroot url usages

Bug Fixes:
    *Bug-#040861: Fixed day time saving issue for updating teacher Availability
    *Bug-#043168: Failed payment page, fix contactus url 
    *Bug-#043166: Group class price fix on checkout page 
    *Bug-#043165: Hide lessons count from reviews section
    *Bug-#043161: Duplicate entries for issue report
    *Bug-#043111: Removed Twitter settings
    *Bug-#043080: Trial & Group Class on Google Calendar
    *Bug-#043081: Updated Title on Google Calendar
    *Bug-#043163: After review hide encourge msg for teacher
    *Bug-#043157: Hide attach lesson plan if lesson is cancelled
    *Bug-#042728: Time gap and end lesson related issues
    *Bug-#043338: Fixed header Link display order
    *Bug-#043357: Sorted languages alphabetical order
    *Bug-#043323: Showing year from 1970
    *Bug-#043349: Sorted spoken/teach language in alphabetical order
    *Bug-#043155: Added Language and upcoming filters in Group class
    *Bug-#043148: Line break in biography on teacher profile
    *Bug-#043147: Changed hourly rate label to "Starts From"
    *Bug-#042739: Group classes on Teacher and Learner dashboard
    *Bug-#043384: Slot booking with single click
    *Bug-#043459: Fixed home page slider display order as set by admin
    *Bug-#043460: Hide Disabled FAQs from frontend
    *Bug-#043461: Show only active blog posts count with Blog categories
    
------------------------------
Release Version: TV-2.1.0.20200826
Release Date: 2020/08/26

New Features:
  * task-72959 :- Integrate Paypal Payouts API
  
Enhancements: N/A

Bug Fixes:
    *Bug-#040861: Disabled group class price update after booking
    *Bug-#040860: Group Class learners limit, managed from admin
    *Bug-#040854 - learner end lesson >> before 5 min >> teacher refresh the page >> teacher don't get option to end the class.
    *Bug-#041046: Show only relevant language for class
    *Bug-#041048: Removed reschedule option for group class
    *Bug-#041217: Cancelled class for 1-1 bookings
    *Bug-#041061: Fixed edit settings permission issue
    *Bug-#041219: Class cancelled by admin, reflect in front
    *Bug-#041343: Removed Need to be scheduled option from admin->orders
    *Bug-#041342: Fixed warning on cancel plan
    *Bug-#041339: Added readonly attribute with Group class Start/End time
    *Bug-#041481 - client issue - base copy - While checking W3C https://validator.w3.org/ site giving few error
    *Bug-#041479 - Gt matrix - Performance issue - PFA
    *Bug-#041472 - Client issue- Teacher dashboard> Settings> Availability tab> There is issue in updating availability and it is not updating the same 
    *Bug-#041414 - There is issue in updating availability and it is not updating the same
    *Bug-#041395 - When user check reports section then amount for last week earning is showing $0 , but graph is showing for some value for earning. which is wrong.
    *Bug-#041341 - When Teacher A create a group class and learner A and learner b book a class>> learner A book a group class and learner B book a group class with apply coupon.>> now both learner join the Class .>>now learner B try to cancel the class then error message i
    *Bug-#041294 - Teacher complete profile page>> when profile not complete >> opening in dashboard menu also and in settings menu also>> need to remove from dashboard
    *Bug-#041293 - Register Teacher >>teacher complete his profile>> username >> should accept Alphanumeric with special character.
    *Bug-#041260 - When Any user Create a Multiple learner or Teacher then first name and last name is used same LIke "Ravi Narang", and multiple Learner and Teacher create with Same "first name and Last Name", and URL for find a teacher is also generate same.
    *Bug-#041059 - Open a Admin section>>Create New admin user in MANAGE ADMIN USER section>>Now give permission READ ONLY to new user.>> open a new user> open payment method screen>>here showing undefined error.
    *Bug-#040892 - user login via Face book with phone no>> enter email screen appear >> user enter email click on submit button >> Message of verification email appear and disappear automatically>> Message should not disappear automatically.
    *Bug-#040876 - Google button and Stretched icon issue in sign up screen.
    *Bug-#040861 - Once Teacher create a group class>> and set a entry fee is $2 and one learner book its class, >After that teacher can edit a class and change the amount to $200. then its wrong.
    *Bug-#040853 - Complete lesson show >> cancel button >> need to remove that.
    #040833 - Teacher side show >> report an issue to support team .>>after teacher made 100% refund. 
    *Bug-#042741 - Teacher Listing top pagination hide before content load
    *Bug-#042738 - Fixed subadmin permissions issue

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
