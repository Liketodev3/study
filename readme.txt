This file shares the details of the Yo!Coach version and change log.

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
    - #049290: We are  facing  404 error in any tutor live site when uer add "/"  at the end of url , the url list is mentioned in below  screen shot url :https://prnt.sc/y4c6yb
    - #048753: Teacher application form> Showing  a error during upload pdf file.
    - #046499: Home page, ipad portrait view> Alignment of  columns in image below not  in symmetrical order when user add maximum and minimum text. Seems to be ok in all other views except Ipad Portrait view. Please have a look one into the screen shot.  Screen shot UR
    - #045400: When the user fills " Send Gift Card " Form, after click on "send gift card" button, gift card amount is automatically added to Manage Gift Cards section in the admin area, While user still on the checkout page. He has not proceeded.

-------------------------------------------------------------------------

Release Number: TV-2.11.6.20210405

Release Date: 2021-04-05

Updates:
    .#73053-update labels
Bugs
    .#051969-change lesson package label
    .#83928-use webroot url for include js file
    .#83928-change label query
    .#051968-fix group class serach form css issue
    .#050298-cancel group class fixed
    .#050897-fic start and end time text issue
    .#051975-fix phone no validation issue
    .#051963-set range of txn amount in admin panel
    .#051964 - Search should be working according to Country wise

-------------------------------------------------------------------------------------

Release Number: TV-2.11.5.20210403
Release Date: 2021-04-03

Updates:
    Task-83928-Country code and phone number validation

Bugs:
    #051957 - The default items per page on the Admin's end needs to be restricted to prevent the fatal error.
    #051930 - When user cancel the lesson(Bulk/single) which is purchased by apply coupon , then error message is showing.
    #051771 - Group class text over ongoing class not coming as correct.
    #051770 - Phone no character length should be 7 to 16. 
    #051199 - Design tweaks, responsive design and dynamic copyright year in email templates
    #051935 - Show cross icon on transactions popup

------------------------------------------------------------

Release Version: TV-2.7.13.20210107
Release Date: 2021/01/07

Updates:
    *Task-80349: Update Stripe to support SCA( Strong Customer Authentication)
    *Task-78843: Upgrade zoom to v1.8.3
    *Task-79596: Paygate Payment gateway integration
    *Task-79596: 2checkout Payment gateway integration
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