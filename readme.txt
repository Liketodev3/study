This file shares the details of the Yo!Coach version and change log.

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


-------------------------------------------------------------------

Release Number: TV-2.21.2.20210625

Release Date: 2021-06-25

Fixes:

    Task-86406-Various fixes as found in system review by team

-------------------------------------------------------------------

Release Number: TV-2.21.1.20210624

Release Date: 2021-06-24

Fixes:

    Task-86405 Contact page recaptcha
    Task-86405 Fixed display hours issue
    Bug-#055355-Removed WIZIQ configuration setting

-------------------------------------------------------------------

Release Number: TV-2.21.0.20210624
Release Date: 2021-06-24

Updates:

Task-86406 Minor tweaks/fixes
Task-86405 UI/UX tweaks and improvements
Task-86405 Added cookie Consent new HTML
Task-84921 Changed design of GDPR request

Fixes:
    #55345
    #54983
    #55340
    #55028

-------------------------------------------------------------------

Release Number: TV-2.20.0.20210622

Release Date: 2021-06-22

Updates:

    *Task-84921: Delete User Personal Details under GDPR

Fixes:

    #052237
    #054100
    #055306
    #055282
    #055191
    #055184
    #051047
    #055150
    #053426
    #054722
    #053859
    #053864
    #054596
    #051713
    #055299

-------------------------------------------------------------------

Release Number: TV-2.19.0.20210618

Release Date: 2021-06-18

Updates:
    *Task-86496: HTML integration - Apply to Teach

Fixes:
    #054442
    #054443
    #051840
    #055182
    #055183
    #054851
    #055022
    #055178
    #055177
    #055121
    #052330
    #055005
    #054983
    #053877
    #051883
    #054873
    #053481
    #050216
    #051411
    #053212
    #053674
    #053498
    #054609
    #054387
    #045765
    #054686
    #054725
    #053770
    #053506
    #052972
    #052099
    #051882
    #051789
    #050898
    #050881
    #049561
    #040857
    #040850
    #051040
    #054505
    #054440
    #054530
    #053678
    #053371
    #049560

Know issues:

    #055184
    #055191
    #055028
    #054638
    #Popup scroll in mobile resolution
    #Dashboard calendar mobile view
    #Dashboard manage lesson upcoming text overlap
    #Arrows icons' rotation issue in RTL

----------------------------------------------------------------

Release Number: TV-2.18.0.20210616

Release Date: 2021-06-16

Updates:

    *Task-86496: HTML integration - Apply to Teach
    
Fixes:
    #054985
    #054864
    #054959
    #054983
    #054957
    #054963
    #054971
    #054972
    #055003
    #055011
    #055022
    #054936
    #054956
    #054851
    #054887
    #054890
    #054962
    #054960
    #054958
    #052330
    #044551
    #048309
    #054853
    #044549
    #052179
    #052430
    #054050
    #050216
    #050836
    #051672
    #051711
    #051714
    #052085
    #055008
    #055027
    #055007
    #054974
    
Enhancements: 

    UI/UX improvements on dashboard and other section
    View Full Availability on teacher listing

--------------------------------------------------------

Release Number: TV-2.17.0.20210609

Release Date: 2021-06-09

*Important Note: From this release onwards, JSON support is required in MySQL.

Updates:
    *Task-85738: HTML integration - Homepage
    *Task-85971: HTML integration - Find a tutor page
    *Task-85882: HTML integration - Tutor profile page
    *Task-86241: HTML integration - Checkout Pages
    *Task-86244: HTML integration - Contact us
    *Task-86242: HTML integration - Group classes
    *Task-86243: HTML integration - About us
    *Task-81998: Support to php7.4

Fixes:
    #049560
    #053371
    #053989
    #054100
    #054394
    #054389
    #053877
    #053867
    #053837
    #053678
    #054530
    #054440
    #054505
    #054442
    #051040
    #052092
    #040850
    #040857
    #049561
    #050881
    #050898
    #051789
    #051882
    #052099
    #052972
    #053506
    #053770
    #054725
    #054686
    #054589
    #045765
    #054607
    #054387
    #054609
    #053498
    #054263
    #054657
    #053674
    #053212
    #051411
    #054729


---------------------------------
Release Number: TV-2.16.0.20210601

Release Date: 2021-06-01

Fixes:
    #054136
    #054100
    #054008
    #053989
    #053984
    #053966
    #053964
    #053481
    #053437
    #053371
    #053008
    #053005
    #052900
    #052674
    #051883
    #049560
    #044333

Enhancements: 

    Admin can change user's password
    Admin can edit robots.txt
    Delete image from cache when user remove or update image

--------------------------------------------------------

Release Number: TV-2.15.0.20210520

Release Date: 2021-05-20

Updates:
    Task-83046-Amazon S3 and CDN
    Task-84083-Wiziq
    Task-84683-Refactored Report a problem
    Task-81797-Volume prices lessons

--------------------------------------------------------

Release Number: TV-2.14.0.20210519

Release Date: 2021-05-19

Updates:
    Task-83884-New Dashboard Design

--------------------------------------------------------

Release Number: TV-2.13.0.20210510

Release Date: 2021-05-10

Updates:

    - Disable Paygate and Paystack in restore DB.
    - Integrate Wiziq conferencing tool
    - URL rewrites
    - Open graph meta tags

---------------------------------------------------------

Release Number: TV-2.12.1.20210503

Release Date: 2021-05-03

Fixes:

    Bug-#053008 - Fix lesson interval on trial booking
    Bug-#053005 - update free-trial duration label

---------------------------------------------------------

Release Number: TV-2.12.0.20210426

Release Date: 2021-04-26

Updates:

    - Add 15 mins booking slot.
    - Give provision to admin to change trial lesson duration.

Fixes:

    Bug-#051847 - Fix checkout page ui for mobile resolution
    Bug-#052412 - Stop learner from reschduling lesson after a window passes
    Bug-#052672 - Fix media deletion on teach-langs section in admin
    Bug-#052674 - Fix issue on req withdrawl submission
    Bug-#052675 - Fix pagination and search issue in Top languages report

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

