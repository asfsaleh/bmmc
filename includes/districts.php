<?php
/**
 * 64 Districts of Bangladesh grouped by Division
 */

function getBangladeshDistricts(): array {
    return [
        'চট্টগ্রাম বিভাগ (Chittagong)' => [
            'Chattogram' => 'চট্টগ্রাম (Chattogram - Port City)',
            'Cox\'s Bazar' => 'কক্সবাজার (Cox\'s Bazar)',
            'Cumilla' => 'কুমিল্লা (Cumilla)',
            'Feni' => 'ফেনী (Feni)',
            'Brahmanbaria' => 'ব্রাহ্মণবাড়িয়া (Brahmanbaria)',
            'Noakhali' => 'নোয়াখালী (Noakhali)',
            'Lakshmipur' => 'লক্ষ্মীপুর (Lakshmipur)',
            'Chandpur' => 'চাঁদপুর (Chandpur)',
            'Khagrachhari' => 'খাগড়াছড়ি (Khagrachhari)',
            'Rangamati' => 'রাঙ্গামাটি (Rangamati)',
            'Bandarban' => 'বান্দরবান (Bandarban)',
        ],
        'ঢাকা বিভাগ (Dhaka)' => [
            'Dhaka' => 'ঢাকা (Dhaka)',
            'Gazipur' => 'গাজীপুর (Gazipur)',
            'Narayanganj' => 'নারায়ণগঞ্জ (Narayanganj)',
            'Tangail' => 'টাঙ্গাইল (Tangail)',
            'Narsingdi' => 'নরসিংদী (Narsingdi)',
            'Manikganj' => 'মানিকগঞ্জ (Manikganj)',
            'Munshiganj' => 'মুন্সীগঞ্জ (Munshiganj)',
            'Faridpur' => 'ফরিদপুর (Faridpur)',
            'Gopalganj' => 'গোপালগঞ্জ (Gopalganj)',
            'Madaripur' => 'মাদারীপুর (Madaripur)',
            'Rajbari' => 'রাজবাড়ী (Rajbari)',
            'Shariatpur' => 'শরীয়তপুর (Shariatpur)',
            'Kishoreganj' => 'কিশোরগঞ্জ (Kishoreganj)',
        ],
        'খুলনা বিভাগ (Khulna)' => [
            'Khulna' => 'খুলনা (Khulna)',
            'Bagerhat' => 'বাগেরহাট (Bagerhat - Mongla Port)',
            'Satkhira' => 'সাতক্ষীরা (Satkhira)',
            'Jashore' => 'যশোর (Jashore)',
            'Jhenaidah' => 'ঝিনাইদহ (Jhenaidah)',
            'Magura' => 'মাগুরা (Magura)',
            'Narail' => 'নড়াইল (Narail)',
            'Kushtia' => 'কুষ্টিয়া (Kushtia)',
            'Chuadanga' => 'চুয়াডাঙ্গা (Chuadanga)',
            'Meherpur' => 'মেহেরপুর (Meherpur)',
        ],
        'বরিশাল বিভাগ (Barishal)' => [
            'Barishal' => 'বরিশাল (Barishal)',
            'Patuakhali' => 'পটুয়াখালী (Patuakhali - Payra Port)',
            'Bhola' => 'ভোলা (Bhola)',
            'Pirojpur' => 'পিরোজপুর (Pirojpur)',
            'Jhalokati' => 'ঝালকাঠি (Jhalokati)',
            'Barguna' => 'বরগুনা (Barguna)',
        ],
        'সিলেট বিভাগ (Sylhet)' => [
            'Sylhet' => 'সিলেট (Sylhet)',
            'Moulvibazar' => 'মৌলভীবাজার (Moulvibazar)',
            'Habiganj' => 'হবিগঞ্জ (Habiganj)',
            'Sunamganj' => 'সুনামগঞ্জ (Sunamganj)',
        ],
        'রাজশাহী বিভাগ (Rajshahi)' => [
            'Rajshahi' => 'রাজশাহী (Rajshahi)',
            'Bogura' => 'বগুড়া (Bogura)',
            'Pabna' => 'পাবনা (Pabna)',
            'Sirajganj' => 'সিরাজগঞ্জ (Sirajganj)',
            'Naogaon' => 'নওগাঁ (Naogaon)',
            'Natore' => 'নাটোর (Natore)',
            'Chapai Nawabganj' => 'চাঁপাইনবাবগঞ্জ (Chapai Nawabganj)',
            'Joypurhat' => 'জয়পুরহাট (Joypurhat)',
        ],
        'রংপুর বিভাগ (Rangpur)' => [
            'Rangpur' => 'রংপুর (Rangpur)',
            'Dinajpur' => 'দিনাজপুর (Dinajpur)',
            'Kurigram' => 'কুড়িগ্রাম (Kurigram)',
            'Gaibandha' => 'গাইবান্ধা (Gaibandha)',
            'Nilphamari' => 'নীলফামারী (Nilphamari)',
            'Panchagarh' => 'পঞ্চগড় (Panchagarh)',
            'Thakurgaon' => 'ঠাকুরগাঁও (Thakurgaon)',
            'Lalmonirhat' => 'লালমনিরহাট (Lalmonirhat)',
        ],
        'ময়মনসিংহ বিভাগ (Mymensingh)' => [
            'Mymensingh' => 'ময়মনসিংহ (Mymensingh)',
            'Jamalpur' => 'জামালপুর (Jamalpur)',
            'Netrokona' => 'নেত্রকোণা (Netrokona)',
            'Sherpur' => 'শেরপুর (Sherpur)',
        ],
    ];
}

function getMarinerRanks(): array {
    return [
        'Master Mariner / Captain (ক্যাপ্টেন)' => 'Master Mariner / Captain',
        'Chief Officer / Chief Mate (চিফ অফিসার)' => 'Chief Officer / Chief Mate',
        'Second Officer (২য় অফিসার)' => 'Second Officer',
        'Third Officer (৩য় অফিসার)' => 'Third Officer',
        'Deck Cadet (ডেক ক্যাডেট)' => 'Deck Cadet',
        'Chief Engineer (চিফ ইঞ্জিনিয়ার)' => 'Chief Engineer',
        'Second Engineer (২য় ইঞ্জিনিয়ার)' => 'Second Engineer',
        'Third Engineer (৩য় ইঞ্জিনিয়ার)' => 'Third Engineer',
        'Fourth Engineer (৪র্থ ইঞ্জিনিয়ার)' => 'Fourth Engineer',
        'Engine Cadet (ইঞ্জিন ক্যাডেট)' => 'Engine Cadet',
        'Electro-Technical Officer (ETO)' => 'Electro-Technical Officer (ETO)',
        'Bosun / Deck Rating (বসান/ডেক রেটিং)' => 'Bosun / Deck Rating',
        'Fitter / Oiler / Wiper (ইঞ্জিন রেটিং)' => 'Fitter / Oiler / Wiper',
        'Chief Cook / Steward (কুক/স্টুয়ার্ড)' => 'Chief Cook / Steward',
        'Ex-Mariner / Shore-based (উপকূলীয়/সাবেক মেরিনার)' => 'Ex-Mariner / Shore-based',
        'Other Marine Professional (অন্যান্য মেরিন পেশাজীবী)' => 'Other Marine Professional'
    ];
}
