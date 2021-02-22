class NIKTranslator
{
    // Get current year and get the last 2 digit numbers
    function getCurrentYear() {
        return (int)date('y');
    }

    // Get year in NIK
    function getNIKYear($nik) {
        return (int)substr($nik, 10, 2);
    }

    // Get date in NIK
    function getNIKDate($nik) {
        return (int)substr($nik, 6, 2);
    }

    function getNIKDateFull($nik, $isFemale) {
        $date = (int)substr($nik, 6, 2);
        if($isFemale) $date -= 40;
        return ($date > 10) ? $date : '0'.$date; 
    }

    // Get subdistrict split postal code
    function getSubdistrictPostalCode($nik, $location) {
        return explode(' -- ', $location['kecamatan'][substr($nik, 0, 6)]);
    }

    // Get province in NIK
    function getProvince($nik, $location) {
        return $location['provinsi'][substr($nik, 0, 2)];
    }

    // Get city in NIK
    function getCity($nik, $location) {
        return $location['kabkot'][substr($nik, 0, 4)];
    }

    // Get NIK gender
    function getGender($date) {
        return ($date > 40) ? 'PEREMPUAN' : 'LAKI-LAKI';
    }

    // Get born month
    function getBornMonth($nik) {
        return (int)substr($nik, 8, 2);
    }

    function getBornMonthFull($nik) {
        return substr($nik, 8, 2);
    }

    // Get born year
    function getBornYear($nikYear, $currentYear) {
        return ($nikYear < $currentYear)
                ? (($nikYear > 10) ? '20'.$nikYear : '200'.$nikYear)
                : (($nikYear > 10) ? '19'.$nikYear : '190'.$nikYear);
    }

    // Get unique code in NIK
    function getUniqueCode($nik) {
        return substr($nik, 12, 4);
    }

    // Get age from NIK
    function getAge($birthday) {
        date_default_timezone_set('Asia/Jakarta');
        $diff = date_diff(date_create($birthday), date_create(date('Y-m-d')));
        return [
            'years' => $diff->y,
            'months' => $diff->m,
            'days' => $diff->d,
        ];
    }

    // Get next birthday
    function getNextBirthday($birthday) {
        date_default_timezone_set('Asia/Jakarta');
        $date = explode('-', date('Y-m-d'));
        $birth = explode('-', $birthday);
        if($date[1] == $birth[1] && $date[2] <= $birth[2]) $date[0] += 1; 

        $births = $date[0].substr($birthday, -6);
        $diff = date_diff(date_create(date('Y-m-d')), date_create($births));
        $y = ($diff->invert) ? -1*$diff->y : $diff->y;
        $m = ($diff->invert) ? -1*$diff->m : $diff->m;
        $d = ($diff->invert) ? -1*$diff->d : $diff->d;

        $txt = '';
        if($y != 0) $txt .= "$y tahun ";
        if($m != 0) $txt .= "$m bulan ";
        if($d != 0) $txt .= "$d hari ";
        $txt .= 'lagi';

        return [
            'text' => $txt,
            'year' => $y,
            'month' => $m,
            'day' => $d,
        ];
    }

    // Get zodiac from bornDate and bornMonth
    function getZodiac($date, $month, $isFemale) {
        if($isFemale) $date -= 40;
        if(($month == 1 && $date >= 20) || ($month == 2 && $date < 19)) return 'Aquarius';
        if(($month == 2 && $date >= 19) || ($month == 3 && $date < 21)) return 'Pisces';
        if(($month == 3 && $date >= 21) || ($month == 4 && $date < 20)) return 'Aries';
        if(($month == 4 && $date >= 20) || ($month == 5 && $date < 21)) return 'Taurus';
        if(($month == 5 && $date >= 21) || ($month == 6 && $date < 22)) return 'Gemini';
        if(($month == 6 && $date >= 21) || ($month == 7 && $date < 23)) return 'Cancer';
        if(($month == 7 && $date >= 23) || ($month == 8 && $date < 23)) return 'Leo';
        if(($month == 8 && $date >= 23) || ($month == 9 && $date < 23)) return 'Virgo';
        if(($month == 9 && $date >= 23) || ($month == 10 && $date < 24)) return 'Libra';
        if(($month == 10 && $date >= 24) || ($month == 11 && $date < 23)) return 'Scorpio';
        if(($month == 11 && $date >= 23) || ($month == 12 && $date < 22)) return 'Sagitarius';
        if(($month == 12 && $date >= 22) || ($month == 1 && $date < 19)) return 'Capricorn';
        return 'Zodiak tidak ditemukan';
    }

    function parse($nik) {
        $location = $this->getLocationAsset();
        
        // Check NIK and make sure is correct
        if($this->validate($nik)) {
            $currentYear = $this->getCurrentYear();
            $nikYear = $this->getNIKYear($nik);
            $nikDate = $this->getNIKDate($nik);
            $gender = $this->getGender($nikDate);

            $nikDateFull = $this->getNIKDateFull($nik, $gender == 'PEREMPUAN');

            $subdistrictPostalCode = $this->getSubdistrictPostalCode($nik, $location);
            $province = $this->getProvince($nik, $location);
            $city = $this->getCity($nik, $location);
            $subdistrict = $subdistrictPostalCode[0];
            $postalCode = $subdistrictPostalCode[1];

            $bornMonth = $this->getBornMonth($nik);
            $bornMonthFull = $this->getBornMonthFull($nik);
            $bornYear = $this->getBornYear($nikYear, $currentYear);

            $uniqueCode = $this->getUniqueCode($nik);
            $zodiac = $this->getZodiac($nikDate, $bornMonth, $gender == 'PEREMPUAN');
            $age = $this->getAge("$bornYear-$bornMonthFull-$nikDateFull");
            $nextBirthday = $this->getNextBirthday("$bornYear-$bornMonthFull-$nikDateFull");

            return [
                'nik' => $nik ?? '',
                'uniqueCode' => $uniqueCode ?? '',
                'gender' => $gender ?? '',
                'bornDate' => "$nikDateFull-$bornMonthFull-$bornYear" ?? '',
                'age' => [
                    'text' => $age['years'].' tahun '.$age['months'].' bulan '.$age['days'].' hari',
                    'year' => $age['years'],
                    'month' => $age['months'],
                    'days' => $age['days']
                ],
                'nextBirthday' => $nextBirthday,
                'zodiac' => $zodiac ?? '',
                'province' => $province ?? '',
                'city' => $city ?? '',
                'subdistrict' => $subdistrict ?? '',
                'postalCode' => $postalCode ?? ''
            ];
        } else {
            return false;
        }
    }

    // Validate NIK and make sure the number is correct
    function validate($nik) {
        $loc = $this->getLocationAsset();
        return strlen($nik) == 16 &&
                $loc['provinsi'][substr($nik, 0, 2)] != null &&
                $loc['kabkot'][substr($nik, 0, 4)] != null &&
                $loc['kecamatan'][substr($nik, 0, 6)] != null;
    }

    // Load location assets like province, city, and subdistricts
    // from local json data
    function getLocationAsset() {
        $result = file_get_contents('wilayah.json');
        return json_decode($result, true);
    }
}
