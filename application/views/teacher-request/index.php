<?php defined('SYSTEM_INIT') or die('Invalid Usage.');
$applyTeachFrm->setFormTagAttribute( 'class', 'form' );
$applyTeachFrm->setFormTagAttribute('onsubmit', 'setUpSignUp(this); return(false);');
$applyTeachFrm->getField('user_email')->setFieldTagAttribute('placeholder',Label::getLabel('LBL_Email',$siteLangId));
$applyTeachFrm->getField('user_password')->setFieldTagAttribute('placeholder',Label::getLabel('LBL_Password',$siteLangId));
$applyTeachFrm->getField('btn_submit')->setFieldTagAttribute('class','btn btn--secondary btn--large btn--block ');

$applyTeachFrm->getField('email')
?>
<section class="section padding-0">
        <div class="slideshow full-view-banner">
            <picture class="hero-img">
                <img src="<?php echo CONF_WEBROOT_URL.'images/hero_1.jpg' ?>" alt="">
            </picture>
        </div>

        <div class="slideshow-content">
            <h1><?php echo Label::getLabel('LBL_Apply_To_Teach',$siteLangId); ?></h1>
            <p><?php echo Label::getLabel('LBL_Apply_to_Teach_Descritpion',$siteLangId);  ?></p>
            <div class="form-register">
            <?php echo $applyTeachFrm->getFormTag(); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="field-set">
                                <div class="field-wraper">
                                    <div class="field_cover">
                                      <?php echo $applyTeachFrm->getFieldHTML('user_email'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="field-set">
                                <div class="field-wraper">
                                    <div class="field_cover">
                                    <?php echo $applyTeachFrm->getFieldHTML('user_password'); ?>
                                        <a href="javascript:0;" class="password-toggle">
                                            <span class="icon">
                                                <svg id="SHOW-password" xmlns="http://www.w3.org/2000/svg" width="16.2" height="17.134" viewBox="0 0 16.2 17.134">
                                                    <path id="Path_6420" data-name="Path 6420" d="M13.685,15.853a7.764,7.764,0,0,1-4.4,1.375,8.437,8.437,0,0,1-8.1-7.269,9.083,9.083,0,0,1,2.5-4.9L1.339,2.536,2.4,1.393,17.222,17.384l-1.059,1.142-2.478-2.673ZM4.74,6.2A7.383,7.383,0,0,0,2.71,9.96a7.171,7.171,0,0,0,3.846,5.031,6.307,6.307,0,0,0,6.038-.316l-1.518-1.638A3.187,3.187,0,0,1,6.9,12.532a3.852,3.852,0,0,1-.468-4.507ZM9.965,11.84,7.538,9.222a2.136,2.136,0,0,0,.419,2.166,1.774,1.774,0,0,0,2.008.452Zm5.909,1.829L14.8,12.514A7.509,7.509,0,0,0,15.852,9.96,7.262,7.262,0,0,0,12.72,5.324a6.315,6.315,0,0,0-5.272-.745L6.267,3.3a7.7,7.7,0,0,1,3.014-.614,8.437,8.437,0,0,1,8.1,7.269,9.2,9.2,0,0,1-1.506,3.709Zm-6.8-7.337a3.236,3.236,0,0,1,2.59,1.058,3.8,3.8,0,0,1,.98,2.794L9.073,6.332Z" transform="translate(-1.181 -1.393)" fill="#a2a2a2"></path>
                                                  </svg>
                                            </span>
                                            <span class="icon" style="display: none;">
                                                <svg id="hide-password" xmlns="http://www.w3.org/2000/svg" width="16.2" height="14.538" viewBox="0 0 16.2 14.538">
                                                    <path id="Path_6422" data-name="Path 6422" d="M9.281,3a8.437,8.437,0,0,1,8.1,7.269,8.436,8.436,0,0,1-8.1,7.269,8.437,8.437,0,0,1-8.1-7.269A8.436,8.436,0,0,1,9.281,3Zm0,12.922a6.873,6.873,0,0,0,6.571-5.652,6.873,6.873,0,0,0-6.57-5.647A6.873,6.873,0,0,0,2.71,10.27a6.874,6.874,0,0,0,6.571,5.653Zm0-2.019a3.509,3.509,0,0,1-3.369-3.634A3.509,3.509,0,0,1,9.281,6.634a3.509,3.509,0,0,1,3.369,3.634A3.509,3.509,0,0,1,9.281,13.9Zm0-1.615a1.95,1.95,0,0,0,1.872-2.019A1.95,1.95,0,0,0,9.281,8.25a1.95,1.95,0,0,0-1.872,2.019A1.95,1.95,0,0,0,9.281,12.288Z" transform="translate(-1.181 -3)" fill="#a2a2a2"></path>
                                                  </svg>
                                            </span>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $applyTeachFrm->getFieldHTML('btn_submit'); ?>
                </form>
                <div class="row justify-content-center">
                    <p>By signing up with Yo!Coach, you agree to <a href="javascript:0;" class="color-primary">Terms and Conditions</a> and <a href="javascript:0;" class="color-primary">Privacy Policy</a></p>
                </div>
                <div class="row">
                    <div class="col-6">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="btn btn--block social-button social-button--center social-button--fb-white ">
                            <span class="social-button__media">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28">
                                    <path d="M0 4a4 4 0 0 1 4-4h20a4 4 0 0 1 4 4v20a4 4 0 0 1-4 4H4a4 4 0 0 1-4-4z" fill="#fff"></path><path d="M15.73 23v-7.754h2.6l.39-3.023h-2.99v-1.93c0-.874.243-1.47 1.5-1.47h1.6v-2.7A21.335 21.335 0 0 0 16.5 6a3.641 3.641 0 0 0-3.887 3.994v2.23H10v3.022h2.61V23z" fill="#3b5998"></path></svg>
                            </span>
                            <span class="social-button__label">Facebook</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="btn btn--block social-button social-button--center social-button--google-white ">
                            <span class="social-button__media">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 31 31">
                                    <path fill="#fbbb00" d="M6.87,148.63l-1.079,4.028-3.944.083a15.527,15.527,0,0,1-.114-14.474h0l3.511.644,1.538,3.49a9.25,9.25,0,0,0,.087,6.228Z" transform="translate(0 -129.896)"></path>
                                    <path fill="#518ef8" d="M276.516,208.176a15.494,15.494,0,0,1-5.525,14.983h0l-4.423-.226-.626-3.907a9.238,9.238,0,0,0,3.975-4.717h-8.288v-6.132h14.888Z" transform="translate(-245.787 -195.572)"></path>
                                    <path fill="#28b446" d="M53.865,318.262h0a15.5,15.5,0,0,1-23.356-4.742l5.023-4.112a9.219,9.219,0,0,0,13.284,4.72Z" transform="translate(-28.662 -290.675)"></path>
                                    <path fill="#f14336" d="M52.285,3.568,47.263,7.679a9.217,9.217,0,0,0-13.589,4.826L28.625,8.372h0a15.5,15.5,0,0,1,23.661-4.8Z" transform="translate(-26.891)"></path>
                                </svg>
                            </span>
                            <span class="social-button__label">Google</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- <section class="section section--services">
        <div class="container container--narrow">
            <div class="section__head">
                <h2>Benefits to become a tutor on Yo!Coach?</h2>
            </div>

            <div class="section__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="service service--horizontal">
                            <div class="service__media">
                                <img src="./Yo!Coach_files/55x55_1.png">
                            </div>
                            <div class="service__content">
                                <h3>Earn Money Online</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service service--horizontal">
                            <div class="service__media">
                                <img src="./Yo!Coach_files/55x55_2.png">
                            </div>
                            <div class="service__content">
                                <h3>Work Anywhere, Anytime</h3>
                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service service--horizontal">
                            <div class="service__media">
                                <img src="./Yo!Coach_files/55x55_3.png">
                            </div>
                            <div class="service__content">
                                <h3>Teach on Your Schedule.</h3>
                                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service service--horizontal">
                            <div class="service__media">
                                <img src="./Yo!Coach_files/55x55_4.png">
                            </div>
                            <div class="service__content">
                                <h3>Manage Your Students</h3>
                                <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus,</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service service--horizontal">
                            <div class="service__media">
                                <img src="./Yo!Coach_files/55x55_5.png">
                            </div>
                            <div class="service__content">
                                <h3>Find More Students</h3>
                                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service service--horizontal">
                            <div class="service__media">
                                <img src="./Yo!Coach_files/55x55_6.png">
                            </div>
                            <div class="service__content">
                                <h3>Safety and Security</h3>
                                <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus,</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section " style="background-color: var(--color-gray-100);">
        <div class="container container--narrow">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="section__head align-left margin-bottom-5">
                        <h2>Teach students from over 180 countries</h2>
                    </div>
                    <ul class="list-group list-group--line margin-bottom-5">
                        <li class="list-group--item">Steady stream of new students</li>
                        <li class="list-group--item">Smart calendar</li>
                        <li class="list-group--item">Interactive classroom</li>
                        <li class="list-group--item">Convenient payment methods</li>
                        <li class="list-group--item">Training webinars</li>
                        <li class="list-group--item">Supportive tutor community</li>
                    </ul>
                    <button class="btn btn--secondary ">Apply to Teach</button>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="media_group">
                        <div class="media_group--item">
                            <div class="ratio ratio--4by3">
                                <img src="./Yo!Coach_files/4_3.png" alt="">
                            </div>
                        </div>
                        <div class="media_group--item  media_group--item-small">
                            <div class="ratio ratio--4by3">
                                <img src="./Yo!Coach_files/4_3-2.png" alt="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="section section--services">
        <div class="container container--narrow">
            <div class="section__head ">
                <h2>How to become a tutor on Yo!Coach?</h2>
            </div>

            <div class="section__body">
                <div class="service--wrapper">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="service service--vertical">
                                <div class="service__media">
                                    <img src="./Yo!Coach_files/register.png">
                                </div>
                                <div class="service__content">
                                    <h3>Register on Yo!Coach</h3>
                                    <p>Excepteur sint proident, occaecat cupidatat non proident, culpa qui officia velit, sed quia non deseruntadipisci proident,</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service service--vertical">
                                <div class="service__media">
                                    <img src="./Yo!Coach_files/cv.png">
                                </div>
                                <div class="service__content">
                                    <h3>Complete Profile</h3>
                                    <p>Excepteur sint proident, occaecat cupidatat non proident, culpa qui officia velit, sed quia non deseruntadipisci proident,.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service service--vertical">
                                <div class="service__media">
                                    <img src="./Yo!Coach_files/online-learning.png">
                                </div>
                                <div class="service__content">
                                    <h3>Start Teaching</h3>
                                    <p>Excepteur sint proident, occaecat cupidatat non proident, culpa qui officia velit, sed quia non deseruntadipisci proident,.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--cta" style="background-image:url(images/cta_2.jpg);">
        <div class="container container--narrow">
            <div class="cta-content">
                <h2 class="margin-bottom-5">Do you want to become a teacher on Yo!Coach?</h2>
                <p class="margin-bottom-5">Connect with thousands of learners around the world and teach from your living room</p>
                <button class="btn btn--secondary btn--large ">Apply Now</button>
            </div>
        </div>
    </section>

    <section class="section section--faq">
        <div class="container container--narrow">
            <div class="section__head">
                <h2>Questions? We Have Answers</h2>
            </div>
            <div class="faq-cover">
                <div class="faq-container">
                    <div class="faq-row faq-group-js is-active">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> I am a new teacher. How do I start lesson?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: block;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> I am a new teacher. How does Yo!Coach work?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> How do I Register with Yo!Coach?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> How do I Change my password?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> How do reviews work?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> How to contact Yo!Coach?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> Where is my confirmation email?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>

                    <div class="faq-row faq-group-js">
                        <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="faq-title faq__trigger faq__trigger-js">
                            <h5> I can't sign in to my account?</h5>
                        </a>
                        <div class="faq-answer faq__target faq__target-js" style="display: none;">
                            <p>Yo!Coach is an online language-learning platform that connects language learners with qualified language teachers for private sessions through live video chat.</p>

                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>

                            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container container--narrow">
            <div class="contact-cta">
                <div class="contact__content">
                    <h3>Can't find an answer? </h3>
                    <p>Call us at(855) 692-52236 or email contact@yocoach.com</p>
                </div>
                <a href="http://demozo.com/2014//yo-coach-2021/html/apply_to_teach.html#" class="btn btn--primary color-white">Contact Us</a>
            </div>
        </div>
    </section> -->