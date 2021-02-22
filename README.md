# NIK Translator

NIK Translator Berfungsi untuk mengkonversi 16 digit kode NIK menjadi informasi yang bisa dibaca.

### Example Request:
```php
require 'NIK-Translator.php';
$NIK = new NIKTranslator;
print json_encode($NIK->parse('Masukkan NIK disini..'), JSON_PRETTY_PRINT);
```

### Example Response:
NIK dibawah didapat secara gratis dari internet sebagai contoh.
```json
{
    "nik": "3271046504930002",
    "uniqueCode": "0002",
    "gender": "PEREMPUAN",
    "bornDate": "25-04-1993",
    "age": {
        "text": "27 tahun 9 bulan 29 hari",
        "year": 27,
        "month": 9,
        "days": 29
    },
    "nextBirthday": {
        "text": "2 bulan 2 hari lagi",
        "year": 0,
        "month": 2,
        "day": 2
    },
    "zodiac": "Taurus",
    "province": "JAWA BARAT",
    "city": "KOTA BOGOR",
    "subdistrict": "BOGOR BARAT",
    "postalCode": "16116"
}
```


### Other Language:
* [Dart Version by yusriltakeuchi](https://github.com/yusriltakeuchi/nik_validator)
* [JavaScript Version by fauzan121002](https://github.com/fauzan121002/nik-validator)
