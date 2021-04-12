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
    - #045754: Dates of lessons still appear in English - both the day of the week and the month. That happens in the tutor/student profile as well as in the lesson itself. Screenshots attached. https://bizixx.fatbit.com/attachment/download/MjczNTc3LTE2MDYyMjEwODVfU2M%3
    - #049899: Email Notification is missing> When teacher is placing wallet withdrawal request admin does not receive any notification through email.
    - #049783: When user multiple click on statistics button then graphical representation has changed
    - #050923: Change week start and end date timezone

-------------------------------------------------------------------------

Release Number: TV-2.11.8.20210409
Release Date: 2021-04-09

Bugs
    Fix db updates
    .#050923: Change week start and end date timezone.

--------------------------------------------------------------------------

Release Number: TV-2.11.7.20210408
Release Date: 2021-04-08

Bugs
    Fix db updates
    .#052171: In multiple slot>> during changes lesson price and lesson time>> price is showing different for single and bulk lesson.

--------------------------------------------------------------------------

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
============================================================================================

Release Number: TV-2.11.4.20210402
Release Date: 2021-04-02

Bugs:
    #051769 - The refund percentage for group class does not match the general flow.
    #051839 - The Search class only responds for "English Language" and not for other langauges. 
    #051771 - Group class text over ongoing class not coming as correct.
    #051877 - When user purchase a lesson then in need to be schedule stage>> when user cancel the lesson then got 50% refund, which is wrong, it should be 100% refund.
    #051847 - The payment methods distorts on changing the languages under "Group Class"
    #051884 - In mata tag management >>Search is not working properly. 
    #051883 - In meta tag management >> when user create a metatag for teacher, blog, cms and for english language only. then it will create of rest languages also.
    #051845 - The change in language affects the language offered field on the checkout page.
    #051828 - Language specific data not coming
    #051798 - One 2 one class, Student report the issue, emails not coming coorect. 
    #051797 - Need to remove or hide email templets as its functionality not working. 
    #051796 - The refund percentage for the student for group class should be limited to a particular time slot, let suppose before 24 hours.
    #051776 - In admin>> meta tag management>> Blog post>> "Blog title column" is missing.
    #051774 - The Admin is able to edit the already completed group class.
    #051773 - Edge - Checkout page - Payment methods not coming 
    #051732 - When user login with mobile number and password then facing a issue of email verification.
    #051731 - When user login with facebook and click on side area and cancel the facebook pop up then JSON error is showing. 
    #051729 - When user login with facebook's email id and password then error message is showing. 
    #051663 - From user >> Teacher listing page >> clicking on favorite button for some time >> Favorite button >> text disappear 
    #051461 - Admin >> dashboard >> total revenew from lesson>> need to set the ui
    #051408 - User >> teacher menu >> clicking on one teacher show 404
    #049973 - Email content not decoded in outlook , yopmail etc
    #048614 - On the home page- go t search a teacher.
    #051840 - Fix pwa icons validation messages
    #051844 - PWA - Show existing icons with the file upload fields

============================================================================================


Release Number: TV-2.11.3.20210331
Release Date: 2021-03-31

Bugs:
    #051703

============================================================================================
Release Number: TV-2.11.2.20210331
Release Date: 2021-03-31

Bugs:
    #051733
    #051732
    #051731
    #051729
    #051706
    #051703
    #051685
    #051671
    #051666
    #051654
    #051647
    #051630
    #051624
    #051461
    #051408
    #051365
    #049973
    #049647
    #048614
    #045564
    #051663

======================================================================================

Release Number: TV-2.11.1.20210326
Release Date: 2021-03-26

Bugs:
    
    . #051647
    . #051645
    . #051630
    . #051629
    . #051624
    . #051602
    . #051600
    . #051568
    . #051458
    . #051451
    . #051408
    . #051396
    . #051366
    . #051365
    . #051276
    . #051255
    . #051184
    . #051013
    . #050897
    . #050853
    . #050298
    . #049973
    . #049647
    . #049602
    . #048965
    . #048614
    . #048531
    . #047837
    . #047762
    . #047386
    . #045564
    . #045497
    . #044726
    . #044492
    . #044264
    . #051630
    . #051629


===================================================================================================================
Release Number: TV-2.11.0.20210317
Release Date: 2021-03-17

Updates:
    Task-#81802-Lesson reschedule and cancellation report

Bugs:
    Bug-#050724 - Admin >> need to remove.
    Bug-#050538 - #050538 - In Group class >>Why user can not set his group class price more than highest lesson package rate in website.
    Bug-#050556 - If user did not join a lesson even session start time is crossed. but time left view is showing, which is wrong.
    Bug-#050350 - Need to add validation alert , bulk price should always be less than single lesson rate, other wise teacher will not display if bulk price of any lesson is greater than existing maximum single lesson rate of website teacher list.
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
    Bug-#051007 - Confirm end lesson >> popup come mutiple times.
    Bug-#050890 - Once User registered, then No loader is showing during waiting or delay time.
    Bug-#050882 - Fatal error >> when teacher book a teacher the fatal error appear
    Bug-#050860 - When class time come and teacher and learner open lesson to connect >> on join lesson button need to add loader .
    Bug-#050540 - When leaner schedule a group class and admin delete or cancel a group class then variation or changes are not reflect properly on front end.Sta
    Bug-#050774 -  2CHECKOUT PAGE >> need to add card checkout validations
    Bug-#050764 - Student >> buy lesson >> switching slot duration >> lesson package become non selectable >> and student able to do payment of 0 charges>> mean he able to buy the same in free (Branch name :-  task_80518_multiple_slot_bookings)
    Bug-#050667 - Due to top header view/bar view i can not see some features and data.
    Bug-#050548 - When user upload a profile pic via iphone camera by scroll Zoom process then it is upload image but it take time.
    Bug-#050550 - When user upload a picture from mobile(iphone) via zoom process then its upload success fully in mobile > but its not displaying at profile view and desktop end also.
    Bug-#051188 - When any learner click on teacher whose username is not fill by teacher. then facing a 404 error.
    Bug-#050205 - UI bug- add red colour in teacher setting tab
    Bug-#051184 - In teacher Profile screen>> when user set his profile, now delete the weekly availability, now press save>> then facing a json error.
    Bug-#050836 - Learner side >> teacher description page >> Ui Break for first time on pageload.  
    Bug-#051255 - The same teaching language showing multiple times on the teacher's profile page.
    Bug-#050987 - User side >> group class >> today should be on top.
    Bug-#050953 - Apple touch Icons are not working (404), after updated from admin panel.
    Bug-#050948 - Student>> teacher menu >> click on any teacher
    Bug-#050930 - When teacher add two-three languages,with different time slots then Price is not match for language
    Bug-#050918 - Checkout page >> Language and duration not changing on selecting the Radio buttons
    Bug-#050904 - As per discussion once lesson complete then commission report vary. but if user report an issue and resolve the issue by teacher by 50% ,100% , Un-schedule ,then commission report remain same
    Bug-#050902 - Ui need to fix
    Bug-#050890 - Once User registered, then No loader is showing during waiting or delay time
    Bug-#050878 - When user try to add Availability only 45 minutes for the day then user can't.
    Bug-#044323 - When learner/teacher update a profile picture then plugin showing for cropping the image. but plugin is not working working as standard feature
    Bug-#044694 - "Find a teacher> subjects in chronological order in the subjects dropdown If admin side you will reorder the subjects it is not showing in the same order at frontend"
    Bug-#044725 - When user schedule a 30 minutes lesson at 1 pm and and right now time is 12:59 pm. then time left is showing 1 minutes.
    Bug-#045490 - In Admin side>>Sitemap>> there is suggestion regarding the xml and html.
    Bug-#045624- There is issue in availability screen. 1.User can two slow together without difference of 30 minutes at end of day. 2.When user update the availability of next of "booking slots area" then issue is showing in Availability screen.
    Bug-#045624- There is issue in availability screen. 1.User can two slow together without difference of 30 minutes at end of day. 2.When user update the availability of next of "booking slots area" then issue is showing in Availability screen.
    Bug-#046184-Emails> Scheduled lesson reminder - table formatting creates an additonal row in the emails
    Bug-#050930-When teacher add two-three languages,with different time slots then Price is not match for language.               [Build: TV-2.1.1.20210304]
    Bug-#050902-Ui need to fix               [Build: TV-2.10.0.20210304]
    Bug-#050878-When user try to add Availability only 45 minutes for the day then user can't.               [Build: TV-2.10.0.20210304]
    Bug-#050851-In search module >> When any learner try to search a teacher with select his/her  Country, Accent, teacher level and then select his /her then search is not wotking.               [Build: TV-2.10.0.20210304]
    Bug-#050731-Admin >> need to highlight the selected section >> now first one remain highlighted.               [Build: TV-2.10.0.20210304 ]
    Bug-#050563-PWA is not working for iphone- chrome browsers.               [Build: TV-2.9.0.20210223]
    Bug-#050216-Here I am test the speed of teachers listing page. and result is mention below:-  Result URL:-https://developers.google.com/speed/pagespeed/insights/?url=https%3A%2F%2Fbeta.teach.yo-coach.com%2Fteachers&tab=desktop   Please check attached screen shot  wit               [Build: V2.1]
    Bug-#049564-When user do default item per page is 0 then few error is showing and apply to teach button is not working in about us screen.               [Build: TV-2.7.12.20210206]
    Bug-#048531-Misspelled class name (Labels instead of Label)               [Build: RV-2.1]
    Bug-#048026-After make  slot in availability and  weekly availability then its not working properly.               [Build: TV-2.7.13.20210107]
    Bug-#047747-Import > The addition of new labels shouldn't be allowed
    Bug-#047721-When any learner scheduling a lesson for particular week(next) which Availability was not save by related teacher then learner can not book a lesson.               [Build: TV-2.7.13.20210107]
    Bug-#050829-Payment through Autrourize.net on wrong data show invalid data               [Build: TV-2.10.0.20210304 ]
    Bug-#050813-Teacher application form >> able to add png also in resume but below text its not given               [Build: TV-2.10.0.20210304 ]
    Bug-#050774-2CHECKOUT PAGE >> need to add card checkout validations               [Build: TV-2.10.0.20210304 ]
    Bug-#050773-2 checkout >> on entering invalid data >> loader keep on revolving                [Build: TV-2.10.0.20210304 ]
    Bug-#050764-Student >> buy lesson >> switching slot duration >> lesson package become non selectable >> and student able to do payment of 0 charges>> mean he able to buy the same in free               [Build: TV-2.10.0.20210304 ]
    Bug-#050667-Due to top header view/bar view i can not see some features and data.               [Build: TV-2.2.2.20210105 ]
    Bug-#050562-There are some major issue in  meta tags management.mata tag feature is not working properly.               [Build: TV-2.9.0.20210223]
    Bug-#050557-In meta tag management>> when user search any  keyword then undefined error is displaying.               [Build: TV-2.9.0.20210223]
    Bug-#050552-When user did not upload a profile pic or picture taking time to upload, on this time>Preview/default image is showing incorrect.               [Build: TV-2.9.0.20210223]
    Bug-#050550-When user upload a picture from mobile(iphone)  via zoom process then its upload success fully in mobile > but its not displaying at profile view and desktop end also.               [Build:  TV-2.9.0.20210223]
    Bug-#050548-When user upload a profile pic via iphone camera by scroll Zoom process then it is upload image but it take time.               [Build:  TV-2.9.0.20210223]
    Bug-#050540-When leaner schedule a group class and  admin delete or cancel a group class then variation or changes are not reflect properly on front end.Sta               [Build: TV-2.9.0.20210223]
    Bug-#050298-Make group class multilingual
    Bug-#050205-UI bug- add red colour in teacher setting tab
    Bug-#049973-Email content not decoded in outlook ,  yopmail etc               [Build: TV-2.8.3.20210212]
    Bug-#044692-When teacher and learner scheduled a lesson and complete it>> but in admin section status is showing completed>> but in Status dropdown text field its showing NEED TO BE SCHEDULE, and UI is incorrect.               [Build: TV-2.3.1.20201028]
    Bug-#043910-In teacher end Dashboard>>schedule lesson  and total lesson list are not match with actual.               [Build: TV-2.1.1.20200925]
    Bug-#043797-In sign up, login,forgot password and other forms>> language compatibility is not support.               [Build: TV-2.1.1.20200925]
    Bug-#050836-Learner side >> teacher description page >> Ui Break for first time on pageload.               [Build: TV-2.10.0.20210304]
    Bug-#047815-IN login/sign up screen>> Social media button text is touches with icon in Arabic language.               [Build: TV-2.7.13.20210107]
    Bug-#046817-In home page>> home page slide images>> when user change the language English to Arabic then  Overlay issue is showing.               [Build: 2.1]

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