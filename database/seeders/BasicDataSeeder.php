<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssemblyConstituency;
use App\Models\Relationship;
use App\Models\BloodGroup;
use App\Models\Profession;
use App\Models\Education;
use App\Models\Religion;
use App\Models\Category;
use App\Models\District;
use App\Models\Village;
use App\Models\Caste;
use App\Models\City;

class BasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $religions = [
            [ "name" => "Hindu" ],
            [ "name" => "Muslim" ],
            [ "name" => "Sikh" ],
            [ "name" => "Christian" ],
            [ "name" => "Buddhist" ],
            [ "name" => "Zoroastrian/Parsi" ],
            [ "name" => "Jain" ],
            [ "name" => "Other" ],
        ];

        foreach ($religions as $religion) {
            $exist = Religion::where('name', $religion['name'])->first();

            if (!$exist) {
                $newReligion = new Religion();
                $newReligion->name = $religion['name'];
                $newReligion->save();
            }
        }

        $professions = [
            [ "name" => "Armed Forces" ],
            [ "name" => "Artists - Actor/Writer/Musician" ],
            [ "name" => "Banker" ],
            [ "name" => "Business / Industrialist / Trader/ Self Employed" ],
            [ "name" => "Chartered Accountant" ],
            [ "name" => "Civil Servant / Police" ],
            [ "name" => "Doctor / Medical Professional" ],
            [ "name" => "Educationist - Professor / Teacher" ],
            [ "name" => "Engineer / Scientist" ],
            [ "name" => "Farmer - Agriculturist" ],
            [ "name" => "Gig Worker" ],
            [ "name" => "Homemaker" ],
            [ "name" => "Journalist / Media Professional" ],
            [ "name" => "Karmyogi - Mechanic / Carpenter / Barber etc" ],
            [ "name" => "Labour / Daily wage worker" ],
            [ "name" => "Legal Professional" ],
            [ "name" => "NGO / Trustee / Social Worker" ],
            [ "name" => "Politician / Social Activist" ],
            [ "name" => "Private Sector - Corporate jobs" ],
            [ "name" => "Public sector - Government jobs" ],
            [ "name" => "Religious Preacher" ],
            [ "name" => "Retired" ],
            [ "name" => "Sports and Fitness Professional" ],
            [ "name" => "Street Vendors" ],
            [ "name" => "Student" ],
            [ "name" => "Others" ]
        ];

        foreach ($professions as $profession) {
            $exist = Profession::where('name', $profession['name'])->first();

            if (!$exist) {
                $newProfession = new Profession();
                $newProfession->name = $profession['name'];
                $newProfession->save();
            }
        }

        $categories = [
            [ "name" => "General" ],
            [ "name" => "SC" ],
            [ "name" => "ST" ],
            [ "name" => "OBC" ],
            [ "name" => "Minority" ],
        ];

        foreach ($categories as $category) {
            $exist = Category::where('name', $category['name'])->first();

            if (!$exist) {
                $newCategory = new Category();
                $newCategory->name = $category['name'];
                $newCategory->save();
            }
        }

        $educations = [
            [ "name" => "Less than 10th" ],
            [ "name" => "10th Pass" ],
            [ "name" => "Diploma/ITI" ],
            [ "name" => "12th Pass" ],
            [ "name" => "Graduate" ],
            [ "name" => "Post Graduate" ],
            [ "name" => "PhD and Above" ],
        ];

        foreach ($educations as $education) {
            $exist = Education::where('name', $education['name'])->first();

            if (!$exist) {
                $newEducation = new Education();
                $newEducation->name = $education['name'];
                $newEducation->save();
            }
        }

        $relationships = [
            [ "name" => "Wife" ],
            [ "name" => "Husband" ],
            [ "name" => "Mother" ],
            [ "name" => "Father" ],
            [ "name" => "Son" ],
            [ "name" => "Daughter" ],
            [ "name" => "Grand Father" ],
            [ "name" => "Grand Mother" ],
            [ "name" => "Brother" ],
            [ "name" => "Sister" ],
            [ "name" => "Uncle" ],
            [ "name" => "Aunt" ],
            [ "name" => "Other" ],
        ];

        foreach ($relationships as $relationship) {
            $exist = Relationship::where('name', $relationship['name'])->first();

            if (!$exist) {
                $newRelationship = new Relationship();
                $newRelationship->name = $relationship['name'];
                $newRelationship->save();
            }
        }

        $castes = [
            [ "name" => "Leva Patel" ],
            [ "name" => "Khat Rajput" ],
            [ "name" => "Koli" ],
            [ "name" => "Bharvad / Rabari" ],
            [ "name" => "Sagar" ],
            [ "name" => "Brahman" ],
            [ "name" => "Dalit" ],
            [ "name" => "Bavaji" ],
            [ "name" => "Devipujak" ],
            [ "name" => "Sindhi" ],
            [ "name" => "Vanand" ],
            [ "name" => "Darbar / Kshatriya" ],
            [ "name" => "Luhar / Mistri" ],
            [ "name" => "Gadhvi" ],
            [ "name" => "Kadva Patel" ],
            [ "name" => "Darji" ],
            [ "name" => "Other" ],
        ];

        foreach ($castes as $caste) {
            $exist = Caste::where('name', $caste['name'])->first();

            if (!$exist) {
                $newCaste = new Caste();
                $newCaste->name = $caste['name'];
                $newCaste->save();
            }
        }

        $cities = [
            ["name" => "Akala" ],
            ["name" => "Amarnagar" ],
            ["name" => "Amrapar" ],
            ["name" => "Arab Timbdi" ],
            ["name" => "Bava Pipaliya" ],
            ["name" => "Bheda Pipaliya" ],
            ["name" => "Bordi Samadhiyala" ],
            ["name" => "Champrajpur" ],
            ["name" => "Charan Samadhiyala" ],
            ["name" => "Charaniya" ],
            ["name" => "Dedarva" ],
            ["name" => "Derdi" ],
            ["name" => "Devki Galol" ],
            ["name" => "Haripar" ],
            ["name" => "Jambudi" ],
            ["name" => "Jepur" ],
            ["name" => "Juni Sankali" ],
            ["name" => "Kagvad" ],
            ["name" => "Kerali" ],
            ["name" => "Khajuri Gundala" ],
            ["name" => "Kharachiya" ],
            ["name" => "Khirsara" ],
            ["name" => "Lunagara" ],
            ["name" => "Lunagiri" ],
            ["name" => "Mandlikpur" ],
            ["name" => "Mevasa" ],
            ["name" => "Monpar" ],
            ["name" => "Mota Gundala" ],
            ["name" => "Navi Sankali" ],
            ["name" => "Panchpipla" ],
            ["name" => "Pedhla" ],
            ["name" => "Pipalva" ],
            ["name" => "Pithadiya" ],
            ["name" => "Premgadh" ],
            ["name" => "Rabarika" ],
            ["name" => "Reshamdi Galol" ],
            ["name" => "Rupavati" ],
            ["name" => "Sardharpur" ],
            ["name" => "Seluka" ],
            ["name" => "Station Vavdi" ],
            ["name" => "Thana Galol" ],
            ["name" => "Thorala" ],
            ["name" => "Umrali" ],
            ["name" => "Vadasada" ],
            ["name" => "Valadungra" ],
            ["name" => "Virpur" ]
        ];

        $district = District::where('name', 'Rajkot')->first();

        foreach ($cities as $city) {
            $exist = City::where('name', $city['name'])->first();

            if (!$exist) {
                $newCity = new City();
                $newCity->district_id = $district->id;
                $newCity->name = $city['name'];
                $newCity->save();
            }
        }

        $villages = [
            ["name" => "Umrali", "priority" => 0 ],
            ["name" => "Valadungra", "priority" => 0 ],
            ["name" => "Haripar", "priority" => 0 ],
            ["name" => "Mevasa", "priority" => 0 ],
            ["name" => "Jepur", "priority" => 0 ],
            ["name" => "Virpur", "priority" => 0 ],
            ["name" => "Thorala", "priority" => 0 ],
            ["name" => "Jambudi", "priority" => 0 ],
            ["name" => "Premgadh", "priority" => 0 ],
            ["name" => "Lunagara", "priority" => 0 ],
            ["name" => "Lunagiri", "priority" => 0 ],
            ["name" => "Kerali", "priority" => 0 ],
            ["name" => "Rabarika", "priority" => 0 ],
            ["name" => "Seluka", "priority" => 0 ],
            ["name" => "Kagvad", "priority" => 0 ],
            ["name" => "Pithadiya", "priority" => 0 ],
            ["name" => "Sardharpur", "priority" => 0 ],
            ["name" => "Panchpipla", "priority" => 0 ],
            ["name" => "Mota Gundala", "priority" => 0 ],
            ["name" => "Mandlikpur", "priority" => 0 ],
            ["name" => "Pedhla", "priority" => 0 ],
            ["name" => "Derdi", "priority" => 0 ],
            ["name" => "Monpar", "priority" => 0 ],
            ["name" => "Vadasada", "priority" => 0 ],
            ["name" => "Amarnagar", "priority" => 0 ],
            ["name" => "Khajuri Gundala", "priority" => 0 ],
            ["name" => "Khirsara", "priority" => 0 ],
            ["name" => "Champrajpur", "priority" => 1 ],
            ["name" => "Juni Sankali", "priority" => 0 ],
            ["name" => "Navi Sankali", "priority" => 1 ],
            ["name" => "Bordi Samadhiyala", "priority" => 1 ],
            ["name" => "Thana Galol", "priority" => 0 ],
            ["name" => "Station Vavdi", "priority" => 0 ],
            ["name" => "Charaniya", "priority" => 0 ],
            ["name" => "Charan Samadhiyala", "priority" => 0 ],
            ["name" => "Amrapar", "priority" => 0 ],
            ["name" => "Kharachiya", "priority" => 0 ],
            ["name" => "Rupavati", "priority" => 1 ],
            ["name" => "Dedarva", "priority" => 1 ],
            ["name" => "Pipalva", "priority" => 1 ],
            ["name" => "Akala", "priority" => 1 ],
            ["name" => "Arab Timbdi", "priority" => 1 ],
            ["name" => "Bava Pipaliya", "priority" => 1 ],
            ["name" => "Bheda Pipaliya", "priority" => 0 ],
            ["name" => "Reshamdi Galol", "priority" => 0 ],
            ["name" => "Devki Galol", "priority" => 0 ],
            ["name" => "Jetalsar", "priority" => 1 ],
            ["name" => "Jetalsar Junction", "priority" => 1 ],
        ];

        $assembly = AssemblyConstituency::where('name', 'Jetpur (Rajkot)')->first();

        foreach ($villages as $village) {
            $exist = Village::where('name', $village['name'])->first();

            if (!$exist) {
                $newVillage = new Village();
                $newVillage->assembly_id = $assembly->id;
                $newVillage->name = $village['name'];
                $newVillage->priority = $village['priority'];
                $newVillage->save();
            }
        }

        $bloodGroups = [
            [ "name" => "A+" ],
            [ "name" => "A-" ],
            [ "name" => "B+" ],
            [ "name" => "B-" ],
            [ "name" => "O+" ],
            [ "name" => "O-" ],
            [ "name" => "AB+" ],
            [ "name" => "AB-" ],
        ];

        foreach ($bloodGroups as $bloodGroup) {
            $exist = BloodGroup::where('name', $bloodGroup['name'])->first();

            if (!$exist) {
                $newBloodGroup = new BloodGroup();
                $newBloodGroup->name = $bloodGroup['name'];
                $newBloodGroup->save();
            }
        }
    }
}
