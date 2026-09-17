<?php
/**
 * Bangladesh Merchant Mariners Community (BMMC)
 * Internationalization & Localization Engine (i18n)
 *
 * Default Language: English ('en')
 * Supported Languages: English ('en'), Bengali ('bn')
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Language Resolution & Switching
$supportedLanguages = ['en', 'bn'];
$resolvedLang = 'en'; // Default language: English

if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLanguages, true)) {
    $resolvedLang = $_GET['lang'];
    $_SESSION['bmmc_lang'] = $resolvedLang;
    // Set 1-year persistence cookie
    setcookie('bmmc_lang', $resolvedLang, [
        'expires'  => time() + (86400 * 365),
        'path'     => '/',
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
} elseif (!empty($_SESSION['bmmc_lang']) && in_array($_SESSION['bmmc_lang'], $supportedLanguages, true)) {
    $resolvedLang = $_SESSION['bmmc_lang'];
} elseif (!empty($_COOKIE['bmmc_lang']) && in_array($_COOKIE['bmmc_lang'], $supportedLanguages, true)) {
    $resolvedLang = $_COOKIE['bmmc_lang'];
    $_SESSION['bmmc_lang'] = $resolvedLang;
} else {
    $resolvedLang = 'en'; // Default fallback: English
}

$GLOBALS['bmmc_current_lang'] = $resolvedLang;

/**
 * Get active language code ('en' or 'bn')
 */
function current_lang(): string {
    return $GLOBALS['bmmc_current_lang'] ?? 'en';
}

/**
 * Check if the active language is English
 */
function is_english(): bool {
    return current_lang() === 'en';
}

/**
 * Build target URL preserving current query parameters while setting new language
 */
function lang_url(string $targetLang): string {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $parts = parse_url($uri);
    $path = $parts['path'] ?? '/';
    $params = [];
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $params);
    }
    $params['lang'] = $targetLang;
    return htmlspecialchars($path . '?' . http_build_query($params), ENT_QUOTES, 'UTF-8');
}

/**
 * Core Translation Function
 * Looks up translated string by key; falls back to English or supplied default.
 */
function __($key, $default = ''): string {
    static $dict = null;
    if ($dict === null) {
        $dict = get_bmmc_i18n_dictionary();
    }
    
    $lang = current_lang();
    if (isset($dict[$lang][$key])) {
        return $dict[$lang][$key];
    }
    if ($lang !== 'en' && isset($dict['en'][$key])) {
        return $dict['en'][$key];
    }
    return $default ?: (string)$key;
}

/**
 * Master Localization Dictionary
 * High-fidelity contextual maritime and institutional translations
 */
function get_bmmc_i18n_dictionary(): array {
    return [
        'en' => [
            // Site Identity & Branding
            'site_name' => 'Bangladesh Merchant Mariners Community (BMMC)',
            'site_short_name' => 'BMMC',
            'site_subtitle' => 'Bangladesh Merchant Mariners',
            'site_tagline' => 'For Seafarers\' Welfare, By Seafarers — 100% Apolitical & Non-Profit Humanitarian Service',
            'nav_brand_sub' => 'Bangladesh Merchant Mariners',
            
            // Navigation Links
            'nav_home' => 'Home',
            'nav_our_services' => 'Our Services',
            'nav_blood_portal' => 'Blood Portal',
            'nav_admin' => 'Admin',
            'nav_admin_dashboard' => 'Admin Dashboard',
            'nav_donor_dashboard' => 'Dashboard',
            'nav_login' => 'Login',
            'nav_logout' => 'Logout',
            'nav_volunteer_btn' => 'Become a BMMC Volunteer',
            'nav_live_badge' => 'LIVE',
            'nav_soon_badge' => 'Soon',
            'nav_view_all_services' => 'View All Services Grid',
            'nav_all_services_list' => 'See All Services',
            'nav_mega_title' => 'BMMC Universal Maritime Service Wing',
            'nav_mega_subtitle' => '21 dedicated services protecting the rights, dignity, and welfare of Bangladeshi seafarers and their families worldwide',
            'nav_mega_status' => '1 Live Operational Service • 20 Under Construction',
            'nav_mega_footer_hint' => 'Click any service to view its detailed roadmap and specifications.',
            'nav_theme_toggle_day' => 'Switch to Day mode',
            'nav_theme_toggle_night' => 'Switch to Night mode',
            'lang_name_en' => 'English',
            'lang_name_bn' => 'বাংলা',
            'lang_switch_title' => 'Language / ভাষা',

            // Homepage Hero
            'hero_pill_badge' => 'Bangladesh Merchant Mariners Community (BMMC) • Global Unity of Bangladeshi Seafarers',
            'hero_title_lead' => 'A National Platform Safeguarding Seafarers\' Rights,',
            'hero_title_highlight' => 'Dignity & Maritime Welfare',
            'hero_subtitle' => 'A unified, non-profit initiative connecting Bangladeshi merchant mariners and conscientious citizens. Providing 21 dedicated maritime services including emergency rescue coordination, legal advocacy, cadet mentoring, digital maritime utilities, and an automated life-saving blood donation network.',
            'hero_btn_services' => 'Explore 21 Services',
            'hero_btn_blood' => 'Emergency Blood Portal (Live)',
            'hero_btn_volunteer' => 'Become a BMMC Volunteer',
            'hero_stat_services' => 'Specialized Maritime Services',
            'hero_stat_donors' => 'Registered Donors & Seafarers',
            'hero_stat_support' => 'Emergency Support & Helpline',
            'hero_stat_nonprofit' => 'Apolitical & Non-Profit',
            'count_services' => '21',
            'count_support' => '24/7',
            'count_nonprofit' => '100%',

            // Flagship Spotlight: Blood Network
            'blood_spotlight_pill' => 'Active Operational Wing (Live Service)',
            'blood_spotlight_title' => 'BMMC Blood Donation Network',
            'blood_spotlight_desc' => 'Our flagship operational humanitarian wing. A selfless, intermediary-free blood matching network serving hospital patients nationwide and the families of seafarers serving abroad at sea.',
            'blood_badge_resting' => '4-Month Resting Period Protection',
            'blood_badge_instant' => 'Instant Geolocation Donor Matching',
            'blood_badge_hotline' => '24/7 Dedicated Emergency Coordination',
            'blood_btn_portal' => 'Go to Blood Portal',
            'blood_btn_request' => 'Request Blood',
            'blood_btn_register' => 'Donor Registration',
            'blood_recent_appeals_title' => 'Emergency Blood Appeals',
            'blood_view_all' => 'View All',
            'blood_no_pending' => 'No emergency blood appeals pending at this moment!',
            'blood_help_btn' => 'Help',

            // Services Showcase Section
            'services_hub_badge' => 'BMMC Multidisciplinary Maritime Service Hub',
            'services_section_title' => 'Our 21 Specialized Maritime Services',
            'services_section_subtitle' => 'From cadet preparation to maritime safety, legal defense, seafarer welfare, and digital utilities — every service wing is built with high maritime professionalism.',
            'filter_all' => 'All Services (21)',
            'filter_emergency' => 'Emergency & Welfare (5)',
            'filter_career' => 'Cadets & Career (4)',
            'filter_tools' => 'Smart Tools & Utilities (6)',
            'filter_health' => 'Health & Medical (1)',
            'filter_community' => 'Community & Trust (5)',
            'services_swipe_hint' => 'Swipe horizontally to explore',
            'services_counter_suffix' => 'Services',
            'service_status_live' => 'Active (LIVE)',
            'service_status_soon' => 'In Development',
            'service_btn_explore' => 'Explore Service Roadmap',

            // Volunteer CTA Section
            'volunteer_cta_badge' => 'BMMC Volunteer Network',
            'volunteer_cta_title' => 'Lend Your Expertise, Time & Compassion',
            'volunteer_cta_desc' => 'BMMC is not a commercial enterprise — it is an independent, non-profit humanitarian movement of seafarers and compassionate citizens standing together. Whether in emergency sea rescue, cadet mentorship, blood coordination, or legal advocacy — your contribution makes a lasting impact.',
            'volunteer_cta_alert' => 'Selecting <strong>\'Agree to donate blood\'</strong> in the volunteer form will automatically enrol you in the BMMC Blood Donor Network!',
            'volunteer_cta_box_title' => 'Volunteer Application',
            'volunteer_cta_box_sub' => 'Join us from Chattogram, Dhaka, Khulna, or anywhere at sea across the globe.',
            'volunteer_cta_btn' => 'Become a BMMC Volunteer',

            // Transparency & Pillars
            'transparency_title_1' => '100% Financial Transparency',
            'transparency_desc_1' => 'Every single donation and organizational expenditure is tracked and publicly displayed on our open ledger for complete community accountability.',
            'transparency_title_2' => 'Verified Seafarer Community',
            'transparency_desc_2' => 'Authenticated through official Continuous Discharge Certificates (CDC) and IDs to eliminate impostors and fraudulent agency scams.',
            'transparency_title_3' => 'Selfless Humanitarian Service',
            'transparency_desc_3' => 'Zero political affiliations. Our sole commitment is standing steadfastly by our seafarers, their families, and citizens in need.',

            // Service Detail Page
            'service_not_found_title' => 'Requested Service Not Found',
            'service_not_found_desc' => 'The service you are looking for is currently not in our registry or the link has changed.',
            'breadcrumb_home' => 'Home',
            'breadcrumb_services' => 'Services',
            'service_btn_back_list' => 'All Services List',
            'service_wing_title' => 'BMMC Service Wing',
            'service_wing_sub' => 'Dedicated to Protecting Bangladeshi Seafarers Worldwide',
            'service_btn_volunteer_wing' => 'Volunteer for This Wing',
            'service_under_dev_pill' => 'THIS SERVICE IS UNDER DEVELOPMENT • Launching Soon',
            'service_under_dev_title' => 'This Service is Currently Under Construction',
            'service_under_dev_desc' => 'Valued mariners and visitors, the system architecture, regulatory integrations, and international API linkages for this wing are actively being developed. Register your interest now to be among the first notified upon public rollout.',
            'service_btn_get_notified' => 'Get Notified on Launch',
            'service_notified_alert' => 'Thank you! You will be promptly notified via the BMMC Notification System as soon as this service goes live.',
            'service_problem_heading' => 'Current Challenge & Context',
            'service_solution_heading' => 'BMMC Strategic Solution',
            'service_roadmap_pill' => 'Feature Specifications & Roadmap',
            'service_features_heading' => 'Key Features Included in This Service',
            'service_features_sub' => 'Planned core capabilities designed by our senior marine professionals and software engineers',
            'service_related_heading' => 'Other Services in',
            'service_btn_view_detail' => 'View Details',
            'service_urgent_blood_heading' => 'Looking for Urgent Blood?',
            'service_urgent_blood_sub' => 'Our Blood Donation Network operates 24/7 nationwide.',

            // Blood Portal Page
            'blood_page_title' => 'Blood Donation Network — Bangladesh Merchant Mariners Community (BMMC)',
            'blood_back_hub' => 'Back to Main BMMC Hub',
            'blood_hero_pill' => 'BMMC Active Humanitarian Service Wing • 24/7 Operational',
            'blood_hero_title_lead' => 'In Service of Humanity:',
            'blood_hero_title_highlight' => 'Mariners\' Blood Donation',
            'blood_hero_title_end' => '& Welfare Network',
            'blood_hero_desc' => 'An apolitical, non-profit emergency platform uniting ocean-going seafarers and compassionate citizens. Featuring a 4-month medical resting protocol and direct, intermediary-free donor matching.',
            'blood_btn_emergency_req' => 'Request Blood (Emergency)',
            'blood_btn_donor_reg' => 'Register as a Blood Donor',
            'blood_btn_volunteer_join' => 'Join Volunteer Team',
            'blood_stat_registered' => 'Registered Donors',
            'blood_stat_mariners' => 'Mariners Donors (CDC)',
            'blood_stat_fulfilled' => 'Successful Donations',
            'blood_stat_ongoing' => 'Active Urgent Appeals',
            'blood_active_requests_title' => 'Ongoing Urgent Blood Requests',
            'blood_active_requests_sub' => 'In life-threatening situations, contact immediately or click the help button to coordinate.',
            'blood_btn_new_request' => 'Post New Blood Request',
            'blood_location_label' => 'Location:',
            'blood_bags_label' => 'Needed:',
            'blood_date_label' => 'Date:',
            'blood_status_label' => 'Status:',
            'blood_bags_suffix' => 'Bags',
            'blood_btn_details' => 'Details',
            'blood_how_it_works_badge' => '4-Step Smart Health Protection Automation',
            'blood_how_it_works_title' => 'How Does Automated Blood Coordination Work?',
            'blood_how_it_works_sub' => 'Fastest coordination of technology and empathy with zero commercial middlemen',
            'blood_step1_title' => 'Request Submission',
            'blood_step1_desc' => 'Patient relatives submit an appeal specifying the hospital, required blood group, urgency, and district.',
            'blood_step2_title' => 'Automated Filtering',
            'blood_step2_desc' => 'The system instantly pinpoints available, eligible donors within the exact geographic zone.',
            'blood_step3_title' => 'Instant One-Click Consent',
            'blood_step3_desc' => 'Donors receive instant alerts via SMS and email, allowing them to confirm availability with 1 click.',
            'blood_step4_title' => '4-Month Resting Period',
            'blood_step4_desc' => 'After successful donation, a 120-day resting lockout protects the donor\'s health before returning to available status.',
            'blood_callout_badge' => 'Bangladesh Merchant Mariners Volunteer Team',
            'blood_callout_title' => 'Our Pledge: 100% Non-Profit & Selfless Human Welfare',
            'blood_callout_desc' => 'Formed to stand by the maritime brotherhood and broader society during medical emergencies. Whether you are an active seafarer or an empathetic citizen, you are warmly invited to join our mission.',
            'blood_callout_quote' => '"No worldly remuneration or commercial motive — 100% pure voluntary service!"',
            'blood_callout_box_title' => 'Would You Like to Join Us?',
            'blood_callout_box_sub' => 'Contribute your skills, passion, and time for humanity.',
            'blood_btn_fill_volunteer' => 'Fill Volunteer Application',

            // Volunteer Registration Form
            'vol_page_title' => 'Become a BMMC Volunteer — Application Form',
            'vol_hero_title' => 'Join the BMMC Volunteer Corps',
            'vol_hero_subtitle' => 'Defending mariners\' rights, coordinating emergency rescues, mentoring cadets, and running a nationwide life-saving blood network.',
            'vol_badge_selfless' => 'Zero worldly compensation or salary — 100% voluntary service!',
            'vol_sec1_title' => '1. Primary Identity',
            'vol_type_mariner' => 'Merchant Mariner',
            'vol_type_mariner_sub' => 'Seafarer holding a CDC or SID record',
            'vol_type_general' => 'General Citizen / Supporter',
            'vol_type_general_sub' => 'Empathetic volunteer passionate about seafarers & welfare',
            'vol_cdc_sid_label' => 'CDC / SID Number',
            'vol_cdc_sid_placeholder' => 'e.g. C/O/12345 or SID No.',
            'vol_rank_label' => 'Rank / Designation',
            'vol_rank_select' => '-- Select Rank / Designation --',
            'vol_sec2_title' => '2. Contact Information',
            'vol_name_label' => 'Full Name',
            'vol_name_placeholder' => 'Enter your full legal name',
            'vol_phone_label' => 'Active Mobile / WhatsApp Number',
            'vol_phone_placeholder' => '017XXXXXXXX',
            'vol_email_label' => 'Email Address',
            'vol_email_placeholder' => 'name@example.com',
            'vol_location_label' => 'Your Location / Port Hub',
            'vol_location_select' => '-- Select Location / Port Hub --',
            'vol_sec3_title' => '3. In Which Wings Would You Like to Contribute?',
            'vol_sec3_sub' => 'You may select multiple service wings:',
            'vol_wing_blood' => '🩸 Blood Donation Coordination & Support',
            'vol_wing_emergency' => '🆘 Emergency Response & Seafarer Rescue Team',
            'vol_wing_legal' => '⚖️ Legal Advocacy & Contract Verification (Legal Cell)',
            'vol_wing_cadet' => '🎓 Fresh Cadet Mentoring & Training Guidance',
            'vol_wing_agency' => '🏢 Manning Agency Review & Anti-Fraud Alert',
            'vol_wing_it' => '💻 IT, Web & Digital Seafarer Utilities Development',
            'vol_wing_medical' => '🏥 Medical & Diagnostic Test Centre Liaison',
            'vol_wing_welfare' => '🕊️ Deceased Seafarer Family & Welfare Support Fund',
            'vol_blood_optin_label' => '❤️ Agree to Donate Blood (Blood Donor Enrolment)',
            'vol_blood_optin_desc' => 'Are you willing to donate blood? Keeping this option enabled will automatically register your account with the <strong>BMMC Blood Donor Network</strong> as well. You will receive notifications when a patient in your vicinity urgently requires your blood group.',
            'vol_blood_group_label' => 'Your Blood Group',
            'vol_blood_group_select' => '-- Select Blood Group --',
            'vol_last_donation_label' => 'Approximate Last Donation Date (If Any)',
            'vol_blood_resting_note' => 'BMMC strictly enforces a 4-month (120-day) medical resting period. You will receive no calls for 4 months following a donation.',
            'vol_note_label' => 'Special Skills or Prior Welfare Experience (Optional)',
            'vol_note_placeholder' => 'Briefly mention any past volunteer work, medical background, or maritime experience...',
            'vol_btn_submit' => 'Submit BMMC Volunteer Application',

            // Login Page
            'login_page_title' => 'Login — BMMC',
            'login_tab_login' => '🔑 Login',
            'login_tab_register' => '✨ Register',
            'login_welcome_back' => 'Welcome Back!',
            'login_subtitle' => 'Sign in with your email and password to access your account.',
            'login_demo_heading' => '⚡ Quick Demo Account Login (Click to Auto-fill):',
            'login_demo_admin' => 'Admin',
            'login_demo_mariner' => 'Mariner Donor',
            'login_demo_resting' => 'In Resting Period',
            'login_demo_general' => 'General Donor',
            'login_email_label' => 'Email Address',
            'login_password_label' => 'Password',
            'login_forgot_password' => 'Forgot password?',
            'login_remember_me' => 'Remember me',
            'login_btn_submit' => 'Sign In',
            'login_otp_heading' => 'Instant One-Time Password (OTP) Login',
            'login_otp_desc' => 'Forgot password? Enter your email or phone to receive an OTP.',
            'login_otp_btn' => 'Send OTP Code',
            'login_otp_verify_title' => 'Enter OTP Verification Code',
            'login_otp_btn_verify' => 'Verify OTP & Sign In',
            'login_otp_back_pass' => '← Sign in with password instead',

            // Footer
            'footer_about_title' => 'BMMC',
            'footer_about_sub' => 'Bangladesh Merchant Mariners',
            'footer_about_desc' => 'Bangladesh Merchant Mariners Community (BMMC) — An independent national platform uniting Bangladeshi seafarers worldwide and citizens at home. Dedicated to 21 specialized services across maritime rescue, legal advocacy, cadet development, digital tools, and life-saving blood donation.',
            'footer_wings_title' => 'Our Service Pillars',
            'footer_quick_links_title' => 'Quick Portals',
            'footer_link_blood' => 'Blood Donation Network (Live)',
            'footer_link_emergency' => 'Emergency Response Team',
            'footer_link_legal' => 'Legal Support & ITF Advisory',
            'footer_link_cadet' => 'Fresh Cadet Training Programme',
            'footer_link_seatime' => 'CDC & Sea-Time Calculator',
            'footer_link_all_services' => 'View All 21 Services →',
            'footer_link_blood_req' => 'Blood Appeal Request',
            'footer_link_donor_reg' => 'Donor Registration',
            'footer_link_vol_reg' => 'Volunteer Application',
            'footer_link_login' => 'User Account Login',
            'footer_contact_title' => 'Contact & Hubs',
            'footer_contact_loc' => 'Chattogram Port & Dhaka, Bangladesh',
            'footer_contact_web' => 'bmmc.skillsetup.org',
            'footer_contact_email' => 'welfare@bmmc.org',
            'footer_rights_reserved' => 'All rights reserved.',
            'footer_disclaimer' => '100% Non-Profit, Apolitical & Selfless Seafarers Welfare Network',
            'footer_lang_label' => 'Language / ভাষা:'
        ],
        'bn' => [
            // Site Identity & Branding
            'site_name' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)',
            'site_short_name' => 'BMMC',
            'site_subtitle' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স',
            'site_tagline' => 'মেরিনারদের কল্যাণে, মেরিনারদের দ্বারা — শতভাগ অরাজনৈতিক ও অলাভজনক স্বেচ্ছাসেবা',
            'nav_brand_sub' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স',
            
            // Navigation Links
            'nav_home' => 'হোম',
            'nav_our_services' => 'আমাদের সেবাসমূহ',
            'nav_blood_portal' => 'রক্তদান পোর্টাল',
            'nav_admin' => 'অ্যাডমিন',
            'nav_admin_dashboard' => 'অ্যাডমিন ড্যাশবোর্ড',
            'nav_donor_dashboard' => 'ড্যাশবোর্ড',
            'nav_login' => 'লগইন',
            'nav_logout' => 'লগআউট',
            'nav_volunteer_btn' => 'Become a BMMC Volunteer',
            'nav_live_badge' => 'LIVE',
            'nav_soon_badge' => 'Soon',
            'nav_view_all_services' => 'সমস্ত সেবার গ্রিড ভিউ',
            'nav_all_services_list' => 'সমস্ত সেবাসমূহ দেখুন',
            'nav_mega_title' => 'BMMC সার্বজনীন মেরিটাইম সেবা উইং',
            'nav_mega_subtitle' => 'সমগ্র বিশ্বের বাংলাদেশি নাবিক ও পরিবারের কল্যাণ ও অধিকার সুরক্ষায় নিবেদিত ২১টি সেবা',
            'nav_mega_status' => '১টি সেবা সক্রিয় (Live) • ২০টি নির্মাণাধীন',
            'nav_mega_footer_hint' => 'প্রতিটি সেবার বিস্তারিত ও রোডম্যাপ দেখতে যেকোনো সেবায় ক্লিক করুন।',
            'nav_theme_toggle_day' => 'দিন মোডে পরিবর্তন করুন',
            'nav_theme_toggle_night' => 'রাত মোডে পরিবর্তন করুন',
            'lang_name_en' => 'English',
            'lang_name_bn' => 'বাংলা',
            'lang_switch_title' => 'ভাষা / Language',

            // Homepage Hero
            'hero_pill_badge' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) • বিশ্বজুড়ে বাংলাদেশি নাবিকদের ঐক্য',
            'hero_title_lead' => 'সমুদ্রে নাবিকদের অধিকার রক্ষা,',
            'hero_title_highlight' => 'মর্যাদা ও মানবকল্যাণের',
            'hero_subtitle' => 'বাংলাদেশি মার্চেন্ট মেরিনার এবং সাধারণ নাগরিকদের সম্মিলিত অরাজনৈতিক ও অলাভজনক উদ্যোগ। জরুরি উদ্ধার, আইনি পরামর্শ, ফ্রেশ ক্যাডেট প্রশিক্ষণ, ডিজিটাল স্মার্ট টুলস এবং জীবন রক্ষাকারী রক্তদান নেটওয়ার্ক সহ ২১টি সেবার সমন্বিত প্ল্যাটফর্ম।',
            'hero_btn_services' => 'আমাদের ২১টি সেবাসমূহ দেখুন',
            'hero_btn_blood' => 'জরুরি রক্তদান পোর্টাল (Live)',
            'hero_btn_volunteer' => 'Become a BMMC Volunteer',
            'hero_stat_services' => 'বিশেষায়িত মেরিটাইম সেবা',
            'hero_stat_donors' => 'নিবন্ধিত রক্তদাতা ও নাবিক',
            'hero_stat_support' => 'জরুরি সাপোর্ট ও হেল্পলাইন',
            'hero_stat_nonprofit' => 'অরাজনৈতিক ও অলাভজনক',
            'count_services' => '২১টি',
            'count_support' => '২৪/৭',
            'count_nonprofit' => '১০০%',

            // Flagship Spotlight: Blood Network
            'blood_spotlight_pill' => 'সক্রিয় সেবা উইং (Active Live Service)',
            'blood_spotlight_title' => 'BMMC রক্তদান নেটওয়ার্ক',
            'blood_spotlight_desc' => 'আমাদের প্রথম সম্পূর্ণ কার্যকর মানবকল্যাণমূলক সেবা। দেশজুড়ে চিকিৎসাধীন সাধারণ রোগী ও সাগরে কর্মরত মেরিনার পরিবারের রক্তের প্রয়োজনে সম্পূর্ণ নিঃস্বার্থ ও মধ্যস্থতাকারীবিহীন জরুরি রক্ত মেলানোর স্বয়ংক্রিয় প্ল্যাটফর্ম।',
            'blood_badge_resting' => '৪ মাসের রেস্টিং পিরিয়ড সুরক্ষা',
            'blood_badge_instant' => 'তাৎক্ষণিক এলাকাভিত্তিক ম্যাচিং',
            'blood_badge_hotline' => '২৪/৭ হটলাইন সহায়তা',
            'blood_btn_portal' => 'রক্তদান পোর্টালে যান',
            'blood_btn_request' => 'রক্তের আবেদন',
            'blood_btn_register' => 'ডোনার রেজিস্ট্রেশন',
            'blood_recent_appeals_title' => 'জরুরি রক্তের আবেদনসমূহ',
            'blood_view_all' => 'সকল দেখুন',
            'blood_no_pending' => 'বর্তমানে কোনো জরুরি রক্তের আবেদন পেন্ডিং নেই!',
            'blood_help_btn' => 'সাহায্য',

            // Services Showcase Section
            'services_hub_badge' => 'BMMC মাল্টি-ডিসিপ্লিনারি মেরিটাইম সেবা হাব',
            'services_section_title' => 'আমাদের ২১টি সার্বজনীন সেবাসমূহ',
            'services_section_subtitle' => 'নাবিকদের ক্যারিয়ার শুরু থেকে শুরু করে সমুদ্রে নিরাপত্তা, পারিবারিক কল্যাণ, আইনি অধিকার ও ডিজিটাল ইউটিলিটি—প্রতিটি উইং সম্পূর্ণ পেশাদারিত্বের সাথে ডিজাইন করা হয়েছে।',
            'filter_all' => 'সকল সেবা (২১টি)',
            'filter_emergency' => 'জরুরি সাড়া ও কল্যাণ (৫টি)',
            'filter_career' => 'ক্যারিয়ার ও ক্যাডেট (৪টি)',
            'filter_tools' => 'স্মার্ট টুলস ও ইউটিলিটি (৬টি)',
            'filter_health' => 'স্বাস্থ্য ও ডিরেক্টরি (১টি)',
            'filter_community' => 'কমিউনিটি ও স্বচ্ছতা (৫টি)',
            'services_swipe_hint' => 'ডানে-বামে সোয়াইপ করুন',
            'services_counter_suffix' => 'টি সেবা',
            'service_status_live' => 'সক্রিয় (LIVE)',
            'service_status_soon' => 'নির্মাণাধীন',
            'service_btn_explore' => 'সেবার রোডম্যাপ দেখুন',

            // Volunteer CTA Section
            'volunteer_cta_badge' => 'বিএমএমসি সেচ্ছাসেবী নেটওয়ার্ক',
            'volunteer_cta_title' => 'আপনার মেধা, সময় ও সহমর্মিতা দিয়ে শামিল হোন',
            'volunteer_cta_desc' => 'BMMC কোনো বাণিজ্যিক প্রতিষ্ঠান নয়—এটি মেরিনার ও সাধারণ নাগরিকদের একটি নিঃস্বার্থ যৌথ মানবকল্যাণ আন্দোলন। সাগরে বিপন্ন নাবিকদের উদ্ধার, ক্যাডেটদের প্রশিক্ষণ, রক্তদান সমন্বয় বা আইনি সহমর্মিতা—যেকোনো উইংয়ে আপনি ভলান্টিয়ার হতে পারেন।',
            'volunteer_cta_alert' => 'ভলান্টিয়ার ফর্মে <strong>\'Agree to donate blood\'</strong> চেকলিস্ট সিলেক্ট করলে একই সাথে ব্লাড ডোনার নেটওয়ার্কেও নিবন্ধিত হবেন!',
            'volunteer_cta_box_title' => 'ভলান্টিয়ার আবেদন',
            'volunteer_cta_box_sub' => 'চট্টগ্রাম, ঢাকা, খুলনা বা সমুদ্রের যেকোনো অবস্থান থেকেই যুক্ত হতে পারেন।',
            'volunteer_cta_btn' => 'Become a BMMC Volunteer',

            // Transparency & Pillars
            'transparency_title_1' => '১০০% আর্থিক স্বচ্ছতা',
            'transparency_desc_1' => 'প্রতিটি টাকা অনুদান ও খরচের হিসাব ওয়েবসাইটে উন্মুক্ত লাইভ লেজারের মাধ্যমে সবার পরীক্ষার জন্য প্রদর্শিত হবে।',
            'transparency_title_2' => 'ভেরিফায়েড নাবিক সম্প্রদায়',
            'transparency_desc_2' => 'সরকারি সিডিসি ও পরিচয়পত্র যাচাইয়ের মাধ্যমে নকল পরিচয় ও ভুয়া এজেন্সির প্রতারণা নির্মূলে অঙ্গীকারবদ্ধ।',
            'transparency_title_3' => 'নিঃস্বার্থ মানবসেবা',
            'transparency_desc_3' => 'কোনো রাজনৈতিক সংশ্লিষ্টতা নেই। আমাদের একমাত্র লক্ষ্য বিশ্বজুড়ে মেরিনার এবং দেশের সাধারণ মানুষের পাশে দাঁড়ানো।',

            // Service Detail Page
            'service_not_found_title' => 'অনুরোধকৃত সেবাটি পাওয়া যায়নি',
            'service_not_found_desc' => 'আপনি যে সেবাটি খুঁজছেন তা বর্তমানে আমাদের তালিকায় নেই অথবা এর ঠিকানা পরিবর্তিত হয়েছে।',
            'breadcrumb_home' => 'হোম',
            'breadcrumb_services' => 'সেবাসমূহ',
            'service_btn_back_list' => 'সকল সেবার তালিকা',
            'service_wing_title' => 'BMMC উইং সার্ভিস',
            'service_wing_sub' => 'বাংলাদেশি নাবিকদের সুরক্ষায় নিবেদিত',
            'service_btn_volunteer_wing' => 'এই উইংয়ে ভলান্টিয়ার হোন',
            'service_under_dev_pill' => 'THIS SERVICE IS UNDER DEVELOPMENT • খুব শীঘ্রই উন্মুক্ত হচ্ছে',
            'service_under_dev_title' => 'সেবাটি বর্তমানে নির্মাণাধীন রয়েছে',
            'service_under_dev_desc' => 'সম্মানিত মেরিনার ও ভিজিটরবৃন্দ, এই সেবাটির আর্কিটেকচার ডিজাইন, আইনি ডেটাবেস ও আন্তর্জাতিক এপিআই ইন্টিগ্রেশনের কাজ চলছে। সেবাটি লাইভ হওয়া মাত্র সবার আগে ব্যবহার করতে এখনই আপনার আগ্রহ প্রকাশ করে রাখুন।',
            'service_btn_get_notified' => 'নোটিফিকেশন পান',
            'service_notified_alert' => 'ধন্যবাদ! সেবাটি লাইভ হওয়ার সাথে সাথে BMMC নোটিফিকেশন সিস্টেমের মাধ্যমে আপনাকে অবগত করা হবে।',
            'service_problem_heading' => 'বর্তমান সমস্যা ও পটভূমি',
            'service_solution_heading' => 'BMMC-এর পরিকল্পিত সমাধান',
            'service_roadmap_pill' => 'ফিচার স্পেসিফিকেশন ও রোডম্যাপ',
            'service_features_heading' => 'এই সেবায় যা যা অন্তর্ভুক্ত থাকবে',
            'service_features_sub' => 'আমাদের বিশেষজ্ঞ মেরিন টিম ও সফটওয়্যার ইঞ্জিনিয়ারদের পরিকল্পিত প্রধান বৈশিষ্ট্যসমূহ',
            'service_related_heading' => 'ক্যাটাগরির অন্যান্য সেবাসমূহ',
            'service_btn_view_detail' => 'বিস্তারিত দেখুন',
            'service_urgent_blood_heading' => 'জরুরি রক্তের সন্ধান করছেন?',
            'service_urgent_blood_sub' => 'আমাদের রক্তদান নেটওয়ার্ক ২৪ ঘণ্টা সক্রিয়ভাবে পরিচালিত হচ্ছে।',

            // Blood Portal Page
            'blood_page_title' => 'রক্তদান নেটওয়ার্ক — বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)',
            'blood_back_hub' => 'BMMC মূল পোর্টালে ফিরুন',
            'blood_hero_pill' => 'BMMC সক্রিয় মানবিক সেবা উইং • ২৪/৭ চালু আছে',
            'blood_hero_title_lead' => 'মানবতার সেবায়',
            'blood_hero_title_highlight' => 'মেরিনারদের রক্তদান',
            'blood_hero_title_end' => 'ও কল্যাণ নেটওয়ার্ক',
            'blood_hero_desc' => 'সমুদ্রে কর্মরত মেরিনার এবং দেশের সাধারণ নাগরিকদের যৌথ সহযোগিতায় পরিচালিত একটি অরাজনৈতিক ও অলাভজনক জরুরি প্ল্যাটফর্ম। ৪ মাসের বিশ্রাম সুরক্ষা ও মধ্যস্থতাকারীবিহীন সরাসরি ডোনার ম্যাচিং।',
            'blood_btn_emergency_req' => 'রক্তের আবেদন করুন (Emergency)',
            'blood_btn_donor_reg' => 'রক্তদাতা হিসেবে নাম লেখান',
            'blood_btn_volunteer_join' => 'ভলান্টিয়ার টিমে যোগ দিন',
            'blood_stat_registered' => 'নিবন্ধিত রক্তদাতা',
            'blood_stat_mariners' => 'মেরিনার্স রক্তদাতা (CDC)',
            'blood_stat_fulfilled' => 'সফল রক্তদান',
            'blood_stat_ongoing' => 'জরুরি আবেদন চলমান',
            'blood_active_requests_title' => 'চলমান রক্তের জরুরি আবেদনসমূহ',
            'blood_active_requests_sub' => 'জরুরি প্রয়োজনে রোগীর জীবন বাঁচাতে অবিলম্বে যোগাযোগ করুন বা সাহায্য বাটনে ক্লিক করুন',
            'blood_btn_new_request' => 'নতুন আবেদন করুন',
            'blood_location_label' => 'স্থান:',
            'blood_bags_label' => 'প্রয়োজন:',
            'blood_date_label' => 'তারিখ:',
            'blood_status_label' => 'অবস্থা:',
            'blood_bags_suffix' => 'ব্যাগ',
            'blood_btn_details' => 'বিস্তারিত',
            'blood_how_it_works_badge' => '৪-ধাপের স্মার্ট স্বাস্থ্য সুরক্ষা অটোমেশন',
            'blood_how_it_works_title' => 'স্বয়ংক্রিয় রক্তদান ব্যবস্থাপনা কীভাবে কাজ করে?',
            'blood_how_it_works_sub' => 'কোনো মধ্যস্থতাকারী ছাড়া প্রযুক্তি ও সহমর্মিতার দ্রুততম সমন্বয়',
            'blood_step1_title' => 'আবেদন জমা',
            'blood_step1_desc' => 'রোগীর স্বজনরা হাসপাতালের নাম, ব্লাড গ্রুপ ও এলাকা উল্লেখ করে আবেদন জমা দেন।',
            'blood_step2_title' => 'স্বয়ংক্রিয় ফিল্টারিং',
            'blood_step2_desc' => 'সিস্টেম তাৎক্ষণিকভাবে একই এলাকা ও গ্রুপের প্রস্তুত (Available) ডোনারদের চিহ্নিত করে।',
            'blood_step3_title' => 'বিজ্ঞপ্তি ও ওয়ান-ক্লিক সম্মতি',
            'blood_step3_desc' => 'ডোনারের কাছে তাৎক্ষণিক ইমেইল ও নোটিফিকেশন যায়। ডোনার এক ক্লিকেই "রাজি" হতে পারেন।',
            'blood_step4_title' => '৪ মাসের রেস্টিং পিরিয়ড',
            'blood_step4_desc' => 'সফল রক্তদানের পর ডোনারের শরীর সুস্থ রাখতে ৪ মাস কোনো নতুন কল যাবে না। ৪ মাস পর তিনি স্বয়ংক্রিয়ভাবে প্রস্তুত হবেন।',
            'blood_callout_badge' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স ভলান্টিয়ার টিম',
            'blood_callout_title' => 'শতভাগ অলাভজনক ও নিঃস্বার্থ মানবসেবায় আমাদের প্রত্যয়',
            'blood_callout_desc' => 'মেরিন কমিউনিটি ও সাধারণ মানুষের সংকটময় মুহূর্তে রক্তদান সমন্বয় বা পাশে থাকার জন্য গঠিত এই ভলান্টিয়ার নেটওয়ার্ক। আপনি মেরিনার কিংবা সাধারণ নাগরিক—যে কেউই আমাদের টিমে ভলান্টিয়ার হিসেবে যুক্ত হতে পারেন।',
            'blood_callout_quote' => '"দুনিয়াবি কোনো বিনিময় বা বেতনাদি নেই — পুরোটাই শতভাগ স্বেচ্ছাসেবা!"',
            'blood_callout_box_title' => 'আপনি কি যোগ দিতে চান?',
            'blood_callout_box_sub' => 'আপনার দক্ষতা ও সময় দিয়ে আর্তমানবতার সেবায় শামিল হোন।',
            'blood_btn_fill_volunteer' => 'ভলান্টিয়ার ফরম পূরণ করুন',

            // Volunteer Registration Form
            'vol_page_title' => 'BMMC ভলান্টিয়ার আবেদন ও যোগ দিন',
            'vol_hero_title' => 'BMMC ভলান্টিয়ার টিমে যুক্ত হোন',
            'vol_hero_subtitle' => 'মেরিন কমিউনিটির অধিকার রক্ষা, জরুরি উদ্ধার, ক্যাডেটদের ক্যারিয়ার গাইডেন্স ও দেশব্যাপী রক্তদানের যৌথ নেটওয়ার্ক।',
            'vol_badge_selfless' => 'দুনিয়াবি কোনো বিনিময় বা বেতনাদি নেই — পুরোটাই শতভাগ স্বেচ্ছাসেবা!',
            'vol_sec1_title' => '১. আপনার প্রাথমিক পরিচয়',
            'vol_type_mariner' => 'মার্চেন্ট মেরিনার (Merchant Mariner)',
            'vol_type_mariner_sub' => 'সিডিসি বা এসআইডি ধারী নাবিক',
            'vol_type_general' => 'সাধারণ নাগরিক / শুভানুধ্যায়ী',
            'vol_type_general_sub' => 'মেরিন ও মানবসেবায় আগ্রহী ভলান্টিয়ার',
            'vol_cdc_sid_label' => 'CDC / SID নম্বর',
            'vol_cdc_sid_placeholder' => 'যেমন: C/O/12345 বা SID No.',
            'vol_rank_label' => 'র‍্যাংক / পদবী',
            'vol_rank_select' => '-- পদবী নির্বাচন করুন --',
            'vol_sec2_title' => '২. যোগাযোগের তথ্য',
            'vol_name_label' => 'পূর্ণ নাম',
            'vol_name_placeholder' => 'আপনার পূর্ণ নাম লিখুন',
            'vol_phone_label' => 'সচল মোবাইল নম্বর',
            'vol_phone_placeholder' => '017XXXXXXXX',
            'vol_email_label' => 'ইমেইল ঠিকানা',
            'vol_email_placeholder' => 'name@example.com',
            'vol_location_label' => 'আপনার অবস্থান / বন্দর এলাকা',
            'vol_location_select' => '-- অবস্থান নির্বাচন করুন --',
            'vol_sec3_title' => '৩. কোন কোন সেবায় ভূমিকা রাখতে চান?',
            'vol_sec3_sub' => 'একাধিক ক্ষেত্রে টিক দিতে পারেন:',
            'vol_wing_blood' => '🩸 রক্তদান কার্যক্রম সমন্বয় (Blood Coordination)',
            'vol_wing_emergency' => '🆘 জরুরি সাড়া ও উদ্ধার টিম (Emergency Response)',
            'vol_wing_legal' => '⚖️ আইনি সহায়তা ও চুক্তিপত্র যাচাই (Legal Cell)',
            'vol_wing_cadet' => '🎓 ফ্রেশ ক্যাডেট মেন্টরিং ও ট্রেইনিং গাইড',
            'vol_wing_agency' => '🏢 এজেন্সি রিভিউ ও স্ক্যাম প্রতিরোধ (Anti-Fraud)',
            'vol_wing_it' => '💻 আইটি, ওয়েব ও ডিজিটাল ইউটিলিটি ডেভেলপমেন্ট',
            'vol_wing_medical' => '🏥 মেডিকেল ও টেস্ট সেন্টার যোগাযোগ',
            'vol_wing_welfare' => '🕊️ মৃত সহকর্মীর পরিবার ও ওয়েলফেয়ার সহায়তা',
            'vol_blood_optin_label' => '❤️ Agree to Donate Blood (রক্তদানে সম্মতি)',
            'vol_blood_optin_desc' => 'আপনি কি রক্তদানে আগ্রহী? এই অপশনটি চালু রাখলে আপনার অ্যাকাউন্টটি স্বয়ংক্রিয়ভাবে <strong>BMMC ব্লাড ডোনার নেটওয়ার্কেও</strong> নিবন্ধিত হয়ে যাবে। আপনার এলাকায় কোনো মুমূর্ষু রোগীর জরুরি রক্তের প্রয়োজন হলে আপনি নোটিফিকেশন পাবেন।',
            'vol_blood_group_label' => 'আপনার রক্তের গ্রুপ',
            'vol_blood_group_select' => '-- রক্তের গ্রুপ নির্বাচন করুন --',
            'vol_last_donation_label' => 'সর্বশেষ রক্তদানের আনুমানিক তারিখ (যদি থাকে)',
            'vol_blood_resting_note' => 'BMMC চিকিৎসাবিজ্ঞানসম্মত ৪ মাসের রেস্টিং প্রোটোকল মেনে চলে। রক্তদানের পর ৪ মাস আপনার কাছে কোনো নতুন কল যাবে না।',
            'vol_note_label' => 'আপনার কোনো বিশেষ দক্ষতা বা পূর্ব অভিজ্ঞতা (ঐচ্ছিক)',
            'vol_note_placeholder' => 'অতীতে কোনো সমাজসেবা, চিকিৎসাসেবা বা মেরিটাইম ওয়েলফেয়ারে কাজের অভিজ্ঞতা থাকলে সংক্ষেপে লিখুন...',
            'vol_btn_submit' => 'BMMC ভলান্টিয়ার আবেদন জমা দিন',

            // Login Page
            'login_page_title' => 'লগইন — BMMC',
            'login_tab_login' => '🔑 লগইন',
            'login_tab_register' => '✨ রেজিস্ট্রেশন',
            'login_welcome_back' => 'স্বাগতম আবার!',
            'login_subtitle' => 'আপনার ইমেইল ও পাসওয়ার্ড দিয়ে একাউন্টে প্রবেশ করুন।',
            'login_demo_heading' => '⚡ ডেমো একাউন্ট কুইক লগইন (ক্লিক করুন):',
            'login_demo_admin' => 'Admin',
            'login_demo_mariner' => 'মেরিনার ডোনার',
            'login_demo_resting' => 'বিশ্রামে আছেন',
            'login_demo_general' => 'সাধারণ ডোনার',
            'login_email_label' => 'ইমেইল ঠিকানা',
            'login_password_label' => 'পাসওয়ার্ড',
            'login_forgot_password' => 'পাসওয়ার্ড ভুলে গেছেন?',
            'login_remember_me' => 'আমাকে মনে রাখুন',
            'login_btn_submit' => 'লগইন',
            'login_otp_heading' => 'তাৎক্ষণিক ওটিপি (OTP) লগইন',
            'login_otp_desc' => 'পাসওয়ার্ড মনে নেই? ওটিপি দিয়ে তাৎক্ষণিক লগইন করুন',
            'login_otp_btn' => 'কোড পাঠান',
            'login_otp_verify_title' => 'ওটিপি (OTP) কোড দিন',
            'login_otp_btn_verify' => 'ওটিপি যাচাই করে লগইন',
            'login_otp_back_pass' => '← পাসওয়ার্ড দিয়ে লগইন করুন',

            // Footer
            'footer_about_title' => 'BMMC',
            'footer_about_sub' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স',
            'footer_about_desc' => 'বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC) — বিশ্বজুড়ে সমুদ্রগামী মেরিনার ও দেশের সাধারণ নাগরিকদের ঐক্যবদ্ধ প্ল্যাটফর্ম। জরুরি উদ্ধার, আইনি সহায়তা, ক্যাডেট মেন্টরশিপ, স্মার্ট টুলস ও রক্তদান নেটওয়ার্ক সহ ২১টি সেবায় নিবেদিত।',
            'footer_wings_title' => 'আমাদের সেবা স্তম্ভ',
            'footer_quick_links_title' => 'জরুরি পোর্টাল',
            'footer_link_blood' => 'রক্তদান নেটওয়ার্ক (Live)',
            'footer_link_emergency' => 'ইমার্জেন্সি রেসপন্স টিম',
            'footer_link_legal' => 'আইনি সহায়তা ও ITF',
            'footer_link_cadet' => 'ফ্রেশ ক্যাডেট ট্রেইনিং',
            'footer_link_seatime' => 'সি-টাইম ক্যালকুলেটর',
            'footer_link_all_services' => 'সমস্ত ২১টি সেবাসমূহ →',
            'footer_link_blood_req' => 'রক্তের আবেদন',
            'footer_link_donor_reg' => 'ডোনার রেজিস্ট্রেশন',
            'footer_link_vol_reg' => 'ভলান্টিয়ার আবেদন',
            'footer_link_login' => 'লগইন একাউন্ট',
            'footer_contact_title' => 'যোগাযোগ ও কেন্দ্র',
            'footer_contact_loc' => 'চট্টগ্রাম পোর্ট ও ঢাকা, বাংলাদেশ',
            'footer_contact_web' => 'bmmc.skillsetup.org',
            'footer_contact_email' => 'welfare@bmmc.org',
            'footer_rights_reserved' => 'সর্বস্বত্ব সংরক্ষিত।',
            'footer_disclaimer' => '১০০% অলাভজনক, অরাজনৈতিক ও স্বেচ্ছাসেবী মেরিটাইম নেটওয়ার্ক',
            'footer_lang_label' => 'ভাষা / Language:'
        ]
    ];
}
