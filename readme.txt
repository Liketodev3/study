This file shares the details of YoCoach version and features.

Bug fixes:

    Bug:#045185- Fixed my teachers lessons count

-----------------------------
Release Version: TV-2.4.1.20201105
Release Date: 2020/11/05


Bug fixes:

    Bug:#044927 - Login popup ui fix
    Bug:#044929 - fix js valiation messages on admin login screen

-----------------------------

Release Version: TV-2.4.0.20201104
Release Date: 2020/11/04


Bug fixes:

    Bug:#044696 - Fix spoken langugaes save event
    Bug:#044690 - Fix redirect issue on banners page	
    Bug:#044574 - Make paypal email optional if switched off.
    Bug:#044716 - Fix faq cat actions
    Bug:#044709 - Teacher serach by availability
    Bug:#044724 - Admin change user password to chck for valid password string
    Bug:#044820 - remove banner layout instructions

Updates:
    *Task-77655 - GDPR compliance
    *Task-73053 - Set dimensions in media settings
    *Task-73053 - remove upcoming lessons list from homepage
    *Task-73053 - apply switch on off flashcards for my tutors page
    *Suggestion:#044720 - display general message instead of detailed error for frontend user


-----------------------------

Release Version: TV-2.3.1.20201028
Release Date: 2020/10/28


Bug fixes:

    Bug:#044574 - If admin will disable Paypal payouts if should not ask for Paypal email from teachers
    Bug:#044552 - Blog page banner image should be manageable by the admin	
    Bug:#044573 - Teacher approval email should go to admin's email
    Bug:#044546 - Change "Get started" to "Search for teachers"

Updates:
    *Task-73053: Update fonts
    *Task-76258: Updates for demo version

----------------------------------------------------------------------------------------


Release Version: TV-2.3.0.20201022
Release Date: 2020/10/22


Enhancements:
    *Task-73053: fix live URL for paypal payout and cookie params for localhost
    *Task-73053: add google analytics keys
    *Task-76258: add demo information in header

Bug Resolved:
    *Bug:#044347-fix availability issues
    *Bug:#044335-fix label issue in selectlist
    *Bug:#044331-fix teaching lang save function
    *Bug:#043738-fix undefined user_timezone index issue
    *Bug:#044278-add loader while profile image uplaods


--------------------------------------------------------------------------------------------------------------

Release Version: TV-2.2.0.20201015
Release Date: 2020/10/15

New Features:
    *Task-77275: Google analytics stats on admin dashboard

Enhancements:

    *Task-73053:
        - Show all learners for group class. Rearranged lession details elements.
        - Refinements in showing my lessons
        - Hide Request withdrawal from wallet for learner.
        - Font refinements
        - Code refinement for teacher profile url. Update teacher profile url
        - Admin can swtich flashcards on/off.
        - Optmize tutor search
        - Remove email being sent on send message action.
        - Clean email archives after every month.
        - Update about us page content and remove third party api keys from db
        - Showing home page slider images and certificate validation
        - Hide trail btn if teacher disabled the free trail option
        - Hide the rating section from user profile, if tutor has no rating

   
Bug Resolved:
    *Bug:#044155: Resume certificate optional
    *Bug:#044126: Admin > Languages > Sorting > It is not working
    *Bug:#044114: Navigation management> Add a naviagtion page > Type 'Product Category Page' is not working
    *Bug:#043930: "In Admin section >> when  user do any action on order status then its not totally impact on other feature like cancelled , earning,complted etc."
    *Bug:#043926: In my teacher list>> in some search scenario/or refresh a page>> few teacher's position has changed.
    *Bug:#043915: In teacher/learner end> why lessons whose time has been passed is showing in  schedule list.
    *Bug:#043910: In teacher end Dashboard>>schedule lesson  and total lesson list are not match with actual.
    *Bug:#043905: "Please check doc related to security concern,"
    *Bug:#043904: In Favorite screen >> when user  unfavorite any teacher from favorite list then color of button is not changed and text of button is also not changed.
    *Bug:#043903: In favorite list>>When user remove any teacher from favorite list then  teacher  is still showing in the screen.
    *Bug:#043902: In discount coupon>> when user see add multiple lines description  in admin section then at front end its showing  with long scrolling page.
    *Bug:#043899: In group class>> filters>> need to add one more option in status filter like Expired(whose time has passed).
    *Bug:#043898: "In  group class - Filters> All filter default text is Select> Please add label according to language, status etc."
    *Bug:#043896: Ui issues in group class search view
    *Bug:#043894: Filter is not working properly in teacher screen.
    *Bug:#043884: In request withdrwal form>> when user click on request withdrawal form then it take time to load.a
    *Bug:#043882: In gift card purchase screen>> both teacher/learner end. loader not showing when user search any thing.
    *Bug:#043881: "When learner open a account in ipad and view a schedule group class > now change the  browser from safari to chrome and stay on 15 seconds, do this scenario 2-3 times. now time  difference of left time is vary between teacher and learner."
    *Bug:#043878: Suppose user have $115 in his account>>now user send request for 100 and approved from admin.
    *Bug:#043876: "When learner buy a lesson and admin cancel the lesson after that >> in this  case refund goes to learner account. admin change the order status to ""paid"" but amount is not deduct from learner wallet"
    *Bug:#043851: Blank screen for ipad
    *Bug:#043847: In Group class >> when learner link through Google authorization  and book  a group class >> then scheduled group class  mail is not showing at learner end.
    *Bug:#043815: "Sign up process is not working, when user reset the password without verification done."
    *Bug:#043801: "Home Page > Footer > If the contact details are not added under contact info, remove the option."
    *Bug:#043794: Find a Tech > Price Filter issues
    *Bug:#043793: When user UPLOAD a  profile photo  via camera then picture is showing in  orientation issue and when user try to crop/rotate it then picture view hide.
    *Bug:#043792: When user CHANGE  the profile photo in mobile resolution then error is showing
    *Bug:#043791: When user purchase a lesson  then success message is showing  then redirection of link button is not correct.
    *Bug:#043790: "When user purchase a lesson  then success message is showing then click on ""Web  Portal  Owner"" >> 404 error is showing."
    *Bug:#043781: "Why ""Find a teacher"" button and ""Apply to teacher"" button interchange when user click on ""Find a teacher""."
    *Bug:#043780: When user booked  a free trial  lesson then success message is showing with wrong alignment of text and button.
    *Bug:#043773: When user report any issue and multiple press the submit button then  success message is showing twice.
    *Bug:#043772: Suppose learner purchase 5 lessons(In bulk)  and schedule one lesson >>When teacher reschedule  a lesson>> end lesson pop up is showing again and again. or when teacher view the  reschedule lesson then this issue will happen. Or when teacher open a same u
    *Bug:#043739: "On the landing page in the ""Top rated teachers"" section, the profile picture of one tutor shows upside-down (screen attached). It shows normally in the ""Find a teacher"" section."
    *Bug:#043738: "Check the first name and last name fields in the sign up/login form? It shows an error that ""only letters are accepted"" if the user enters letters from polish alphabet like ""ą ę ś ć ź ó ł ń""."
    *Bug:#043659: TimeZone > Change the time zone drop down values
    *Bug:#043562: In teacher section>> settings>> language>> language(Speak and teach) in this section is not in alphabetical order.
    *Bug:#043538: "Home Page Slider > On changing the home page slider, it is not being updated"
    *Bug:#043486: About Us page > The images should be manageable
    *Bug:#043207: Day time saving update teacher schedule
    *Bug:#043124: Settings > The title is shown as 'Dashboard' instead of settings or profile
    *Bug:#043111: Remove the twitter settings from the admin
    *Bug:#041219: "When Any teacher(Teacher A) Create a group class and learner A and learner B Join a group class with Teacher A, Now admin cancel the  whole group class, but at front end, no notification , no updates are showing.and even leaner A and learner B can do chat"
    *Bug:#041040: Security test report


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
