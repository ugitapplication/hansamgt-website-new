<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

/**
 * The four executives shown on the Management page.
 *
 * The slug is the id of the "read more" dialog, so the published anchors
 * (chairman-message, md-message, ceo-message, coo-message) keep working.
 */
class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'slug' => 'chairman-message',
                'name' => 'Salina Ali',
                'role' => 'Chairperson',
                'modal_name' => 'Ms. Salina Ali',
                'image' => 'images/pages/team/c-1.jpg',
                'image_alt' => 'Salina Ali, Chairperson',
                'teaser' => 'Ms. Salina Ali, a renowned business personality and Chairperson of HANSA Management Ltd. and Unique Group has been the key stakeholder behind the success of Unique Group over the past decades.',
                'bio' => 'Ms. Salina Ali, a renowned business personality and Chairperson of HANSA Management Ltd. and Unique Group has been the key stakeholder behind the success of Unique Group over the past decades. Under her strong leadership and entrepreneurial spirit, the group become one of the leading companies in the country. Ms. Ali accomplished her Bachelor’s (honors) & Master’s degrees in Sociology from the University of Dhaka. She was born in an aristocratic Muslim family in Bangladesh. Apart from active involvement in the business management she is involved in many social and philanthropic activities. Ms. Ali has travelled extensively across the globe in pursuit of business.',
            ],
            [
                'slug' => 'md-message',
                'name' => 'Mohd. Noor Ali',
                'role' => 'Managing Director',
                'modal_name' => 'Mohd. Noor Ali',
                'image' => 'images/pages/team/m-1.jpg',
                'image_alt' => 'Mohd. Noor Ali, Managing Director',
                'teaser' => 'Mr. Mohd Noor Ali is the Managing Director of HANSA Management Ltd. and the founder of Unique Group. He is one of the best-known entrepreneurs in Bangladesh and well recognized among the business community for his responsibility',
                'bio' => 'Mr. Mohd Noor Ali is the Managing Director of HANSA Management Ltd. and the founder of Unique Group. He is one of the best-known entrepreneurs in Bangladesh and well recognized among the business community for his responsibility towards the company’s stable strategy. He aggressively broadened the company’s range of strategic business units, and wherever Unique Group has achieved a dominant position he vigorously contributed towards its growth. Mr. Ali has also been involved in diverse business entities and his most remarkable achievement is he is a self-made person. Today, Mr. Ali is the Managing Director of more than twenty different business concerns. Mr. Ali is a former President of BAIRA, Chairman of Eastern Bank Ltd, as well as Shareholder Directorship of different financial institutions and a number of business bodies. Being a freedom fighter and a philanthropist Mr. Ali’s contribution towards development of the country is truly significant.',
            ],
            [
                'slug' => 'ceo-message',
                'name' => 'Mr. Abu Sadhaq Alam',
                'role' => 'Chief Executive Officer',
                'modal_name' => 'Mr. Abu Sadhaq Alam',
                'image' => 'images/pages/team/Sadhaq.jpeg',
                'image_alt' => 'Abu Sadhaq Alam, Chief Executive Officer',
                'teaser' => 'Mr. Abu Sadhaq Alam serves as the Chief Executive Officer of HANSA Management Limited',
                'bio' => 'Mr. Abu Sadhaq Alam serves as the Chief Executive Officer of HANSA Management Limited, With a diversified career spanning 29 years, including 25 years at Standard Chartered Bank and 4 years in The City Bank, Sadhaq brings a wealth of expertise to enhance our service standards and drive long-term sustainable growth. Having worked across diverse cultural backgrounds in countries such as UAE, Indonesia, Singapore, Lebanon, Sri Lanka, Mauritius, Nepal, Vietnam and India, He has sharpened his skills in global business operations. He holds a Master\'s degree from Dhaka University and B. Com (Hons) from Aligarh Muslim University, India. Sadhaq\'s leadership and dedication will elevate HANSA Management to the new heights, ensuring service excellence along the way.',
            ],
            [
                'slug' => 'coo-message',
                'name' => 'Mohammad Shahedul Haque',
                'role' => 'Chief Operating Officer',
                'modal_name' => 'Mohammad Shahedul Haque',
                'image' => 'images/pages/team/sahed.jpg',
                'image_alt' => 'Mohammad Shahedul Haque, Chief Operating Officer',
                'teaser' => 'Mohammad Shahedul Haque is the Chief Operating Officer of HANSA Management Limited.',
                'bio' => 'More than 21 years of facility management experience providing outstanding management and operational leadership. Shahed was born and raised in the ancient city of Dhaka. He obtained his MBA in Marketing. He has extensive expertise in facility management, property management, hospitality, sales and customer service. Shahed has worked with well-known companies like Shanta Holdings Ltd., Rangs Properties Ltd., Asset Developments & Holdings Ltd., etc. Talented strategic and financial planner who is able to develop solutions to organizational challenges while maintaining financial control and achieving business goals. He is a member of the Rotary International, Lions Clubs International and Secretary of the Rotary Club of Dhaka Paragon (2020-2021). Shahed’s hobbies include music, travel, social media and helping those who are helpless.',
            ],
        ];

        foreach ($members as $index => $member) {
            TeamMember::query()->updateOrCreate(
                ['slug' => $member['slug']],
                [...$member, 'sort' => $index + 1, 'is_active' => true],
            );
        }
    }
}
