<?php

namespace Database\Seeders\Definitions;

final class StationDefinition
{
    public static function getDefinitions(): array
    {
        return [
            11 => [
                '銀座・日比谷・有楽町' => [
                    ['name' => '銀座駅', 'sort_order' => 1],
                    ['name' => '銀座一丁目駅', 'sort_order' => 2],
                    ['name' => '東銀座駅', 'sort_order' => 3],
                    ['name' => '日比谷駅', 'sort_order' => 4],
                    ['name' => '有楽町駅', 'sort_order' => 5],
                ],
                '東京・丸の内・日本橋' => [
                    ['name' => '東京駅', 'sort_order' => 1],
                    ['name' => '日本橋駅', 'sort_order' => 2],
                    ['name' => '水天宮前駅', 'sort_order' => 3],
                    ['name' => '八丁堀駅', 'sort_order' => 4],
                    ['name' => '京橋駅', 'sort_order' => 5],
                    ['name' => '人形町駅', 'sort_order' => 6],
                    ['name' => '三越前駅', 'sort_order' => 7],
                    ['name' => '茅場町駅', 'sort_order' => 8],
                ],
                '渋谷' => [
                    ['name' => '渋谷駅', 'sort_order' => 1],
                    ['name' => '神泉駅', 'sort_order' => 2],
                ],
                '原宿・表参道・青山' => [
                    ['name' => '原宿駅', 'sort_order' => 1],
                    ['name' => '明治神宮前駅', 'sort_order' => 2],
                    ['name' => '代々木公園駅', 'sort_order' => 3],
                    ['name' => '表参道駅', 'sort_order' => 4],
                    ['name' => '外苑前駅', 'sort_order' => 5],
                    ['name' => '青山一丁目駅', 'sort_order' => 6],
                ],
                '赤坂・虎ノ門' => [
                    ['name' => '赤坂駅', 'sort_order' => 1],
                    ['name' => '赤坂見附駅', 'sort_order' => 2],
                    ['name' => '溜池山王駅', 'sort_order' => 3],
                ],
                '六本木・麻布十番・乃木坂' => [
                    ['name' => '六本木駅', 'sort_order' => 1],
                    ['name' => '六本木一丁目駅', 'sort_order' => 2],
                    ['name' => '麻布十番駅', 'sort_order' => 3],
                    ['name' => '乃木坂駅', 'sort_order' => 4],
                ],
                '恵比寿・広尾・白金台' => [
                    ['name' => '恵比寿駅', 'sort_order' => 1],
                    ['name' => '広尾駅', 'sort_order' => 2],
                    ['name' => '白金台駅', 'sort_order' => 3],
                    ['name' => '白金高輪駅', 'sort_order' => 4],
                ],
                '代官山・中目黒' => [
                    ['name' => '代官山駅', 'sort_order' => 1],
                    ['name' => '中目黒駅', 'sort_order' => 2],
                    ['name' => '祐天寺駅', 'sort_order' => 3],
                ],
                '新宿・代々木' => [
                    ['name' => '新宿駅', 'sort_order' => 1],
                    ['name' => '新宿三丁目駅', 'sort_order' => 2],
                    ['name' => '新宿御苑前駅', 'sort_order' => 3],
                    ['name' => '東新宿駅', 'sort_order' => 4],
                    ['name' => '代々木駅', 'sort_order' => 5],
                    ['name' => '新大久保駅', 'sort_order' => 6],
                    ['name' => '大久保駅', 'sort_order' => 7],
                ],
                '四ツ谷・市ヶ谷' => [
                    ['name' => '四ツ谷駅', 'sort_order' => 1],
                    ['name' => '市ヶ谷駅', 'sort_order' => 2],
                    ['name' => '四谷三丁目駅', 'sort_order' => 3],
                    ['name' => '麹町駅', 'sort_order' => 4],
                ],
                '飯田橋・神楽坂' => [
                    ['name' => '飯田橋駅', 'sort_order' => 1],
                    ['name' => '九段下駅', 'sort_order' => 2],
                    ['name' => '神楽坂駅', 'sort_order' => 3],
                    ['name' => '牛込神楽坂駅', 'sort_order' => 4],
                ],
                '御茶ノ水・神保町・水道橋' => [
                    ['name' => '御茶ノ水駅', 'sort_order' => 1],
                    ['name' => '神保町駅', 'sort_order' => 2],
                    ['name' => '水道橋駅', 'sort_order' => 3],
                    ['name' => '後楽園駅', 'sort_order' => 4],
                    ['name' => '新御茶ノ水駅', 'sort_order' => 5],
                ],
                '神田・秋葉原' => [
                    ['name' => '秋葉原駅', 'sort_order' => 1],
                    ['name' => '神田駅', 'sort_order' => 2],
                    ['name' => '岩本町駅', 'sort_order' => 3],
                    ['name' => '小伝馬町駅', 'sort_order' => 4],
                ],
                '池袋・目白' => [
                    ['name' => '池袋駅', 'sort_order' => 1],
                    ['name' => '東池袋駅', 'sort_order' => 2],
                    ['name' => '目白駅', 'sort_order' => 3],
                    ['name' => '大塚駅', 'sort_order' => 4],
                    ['name' => '巣鴨駅', 'sort_order' => 5],
                ],
                '高田馬場・早稲田' => [
                    ['name' => '高田馬場駅', 'sort_order' => 1],
                    ['name' => '早稲田駅', 'sort_order' => 2],
                ],
                '汐留・新橋・浜松町' => [
                    ['name' => '汐留駅', 'sort_order' => 1],
                    ['name' => '新橋駅', 'sort_order' => 2],
                    ['name' => '浜松町駅', 'sort_order' => 3],
                    ['name' => '内幸町駅', 'sort_order' => 4],
                ],
                'お台場・豊洲・湾岸エリア' => [
                    ['name' => '豊洲駅', 'sort_order' => 1],
                    ['name' => '東雲駅', 'sort_order' => 2],
                    ['name' => '新豊洲駅', 'sort_order' => 3],
                ],
                '品川・田町・天王洲' => [
                    ['name' => '品川駅', 'sort_order' => 1],
                    ['name' => '田町駅', 'sort_order' => 2],
                    ['name' => '大井町駅', 'sort_order' => 3],
                ],
                '目黒' => [
                    ['name' => '目黒駅', 'sort_order' => 1],
                    ['name' => '武蔵小山駅', 'sort_order' => 2],
                    ['name' => '西小山駅', 'sort_order' => 3],
                ],
                '五反田・大崎' => [
                    ['name' => '五反田駅', 'sort_order' => 1],
                    ['name' => '大崎駅', 'sort_order' => 2],
                ],
                '自由が丘' => [
                    ['name' => '自由が丘駅', 'sort_order' => 1],
                    ['name' => '都立大学駅', 'sort_order' => 2],
                    ['name' => '学芸大学駅', 'sort_order' => 3],
                    ['name' => '大岡山駅', 'sort_order' => 4],
                    ['name' => '奥沢駅', 'sort_order' => 5],
                ],
                '二子玉川・三軒茶屋' => [
                    ['name' => '二子玉川駅', 'sort_order' => 1],
                    ['name' => '三軒茶屋駅', 'sort_order' => 2],
                    ['name' => '池尻大橋駅', 'sort_order' => 3],
                    ['name' => '駒沢大学駅', 'sort_order' => 4],
                ],
                '中野・高円寺・荻窪' => [
                    ['name' => '中野駅', 'sort_order' => 1],
                    ['name' => '高円寺駅', 'sort_order' => 2],
                    ['name' => '荻窪駅', 'sort_order' => 3],
                    ['name' => '東中野駅', 'sort_order' => 4],
                    ['name' => '阿佐ケ谷駅', 'sort_order' => 5],
                ],
                '吉祥寺・三鷹' => [
                    ['name' => '吉祥寺駅', 'sort_order' => 1],
                    ['name' => '三鷹駅', 'sort_order' => 2],
                    ['name' => '井の頭公園駅', 'sort_order' => 3],
                ],
                '国分寺・多摩' => [
                    ['name' => '国分寺駅', 'sort_order' => 1],
                    ['name' => '東伏見駅', 'sort_order' => 2],
                ],
                '国立・立川' => [
                    ['name' => '立川駅', 'sort_order' => 1],
                    ['name' => '国立駅', 'sort_order' => 2],
                ],
                '八王子' => [
                    ['name' => '八王子駅', 'sort_order' => 1],
                    ['name' => '京王八王子駅', 'sort_order' => 2],
                ],
                '押上・両国・錦糸町' => [
                    ['name' => '錦糸町駅', 'sort_order' => 1],
                    ['name' => '押上駅', 'sort_order' => 2],
                    ['name' => '両国駅', 'sort_order' => 3],
                    ['name' => '亀戸駅', 'sort_order' => 4],
                ],
                '上野・浅草' => [
                    ['name' => '上野駅', 'sort_order' => 1],
                    ['name' => '浅草駅', 'sort_order' => 2],
                    ['name' => '御徒町駅', 'sort_order' => 3],
                    ['name' => '日暮里駅', 'sort_order' => 4],
                    ['name' => '浅草橋駅', 'sort_order' => 5],
                ],
                '北千住・綾瀬' => [
                    ['name' => '北千住駅', 'sort_order' => 1],
                    ['name' => '綾瀬駅', 'sort_order' => 2],
                    ['name' => '亀有駅', 'sort_order' => 3],
                    ['name' => '竹ノ塚駅', 'sort_order' => 4],
                ],
                '築地・門前仲町・木場' => [
                    ['name' => '門前仲町駅', 'sort_order' => 1],
                    ['name' => '築地駅', 'sort_order' => 2],
                    ['name' => '東陽町駅', 'sort_order' => 3],
                    ['name' => '清澄白河駅', 'sort_order' => 4],
                ],
                '葛西' => [
                    ['name' => '葛西駅', 'sort_order' => 1],
                    ['name' => '西葛西駅', 'sort_order' => 2],
                ],
                '下北沢' => [
                    ['name' => '下北沢駅', 'sort_order' => 1],
                    ['name' => '池ノ上駅', 'sort_order' => 2],
                    ['name' => '東北沢駅', 'sort_order' => 3],
                ],
                '明大前・上北沢' => [
                    ['name' => '明大前駅', 'sort_order' => 1],
                    ['name' => '千歳烏山駅', 'sort_order' => 2],
                ],
                '成城・経堂' => [
                    ['name' => '経堂駅', 'sort_order' => 1],
                    ['name' => '成城学園前駅', 'sort_order' => 2],
                ],
                '調布・府中' => [
                    ['name' => '調布駅', 'sort_order' => 1],
                    ['name' => '府中駅', 'sort_order' => 2],
                    ['name' => '仙川駅', 'sort_order' => 3],
                ],
                '町田' => [
                    ['name' => '町田駅', 'sort_order' => 1],
                ],
                '西東京' => [
                    ['name' => 'ひばりケ丘駅', 'sort_order' => 1],
                    ['name' => '東久留米駅', 'sort_order' => 2],
                    ['name' => '保谷駅', 'sort_order' => 3],
                ],
                '板橋' => [
                    ['name' => '板橋駅', 'sort_order' => 1],
                    ['name' => '大山駅', 'sort_order' => 2],
                ],
                '赤羽・王子' => [
                    ['name' => '赤羽駅', 'sort_order' => 1],
                    ['name' => '王子駅', 'sort_order' => 2],
                    ['name' => '東十条駅', 'sort_order' => 3],
                ],
                '練馬・石神井・大泉学園' => [
                    ['name' => '練馬駅', 'sort_order' => 1],
                    ['name' => '石神井公園駅', 'sort_order' => 2],
                    ['name' => '大泉学園駅', 'sort_order' => 3],
                ],
            ],
            12 => [
                '横浜・桜木町・みなとみらい' => [
                    ['name' => '横浜駅', 'sort_order' => 1],
                    ['name' => '桜木町駅', 'sort_order' => 2],
                    ['name' => 'みなとみらい駅', 'sort_order' => 3],
                    ['name' => '関内駅', 'sort_order' => 4],
                    ['name' => '戸塚駅', 'sort_order' => 5],
                ],
                'たまプラーザ・溝の口・センター北' => [
                    ['name' => 'たまプラーザ駅', 'sort_order' => 1],
                    ['name' => '溝の口駅', 'sort_order' => 2],
                    ['name' => 'センター北駅', 'sort_order' => 3],
                    ['name' => 'あざみ野駅', 'sort_order' => 4],
                ],
                '湘南・鎌倉' => [
                    ['name' => '鎌倉駅', 'sort_order' => 1],
                    ['name' => '藤沢駅', 'sort_order' => 2],
                    ['name' => '大船駅', 'sort_order' => 3],
                    ['name' => '辻堂駅', 'sort_order' => 4],
                ],
                '川崎' => [
                    ['name' => '川崎駅', 'sort_order' => 1],
                    ['name' => '武蔵小杉駅', 'sort_order' => 2],
                ],
                '藤沢' => [
                    ['name' => '藤沢駅', 'sort_order' => 1],
                    ['name' => '湘南台駅', 'sort_order' => 2],
                ],
                '平塚' => [
                    ['name' => '平塚駅', 'sort_order' => 1],
                ],
                '海老名' => [
                    ['name' => '海老名駅', 'sort_order' => 1],
                ],
                '厚木' => [
                    ['name' => '本厚木駅', 'sort_order' => 1],
                    ['name' => '厚木駅', 'sort_order' => 2],
                ],
            ],
            13 => [
                '千葉' => [
                    ['name' => '千葉駅', 'sort_order' => 1],
                    ['name' => '千葉中央駅', 'sort_order' => 2],
                ],
                '船橋' => [
                    ['name' => '船橋駅', 'sort_order' => 1],
                    ['name' => '西船橋駅', 'sort_order' => 2],
                    ['name' => '津田沼駅', 'sort_order' => 3],
                ],
                '市川' => [
                    ['name' => '本八幡駅', 'sort_order' => 1],
                    ['name' => '市川駅', 'sort_order' => 2],
                ],
                '柏' => [
                    ['name' => '柏駅', 'sort_order' => 1],
                    ['name' => '南柏駅', 'sort_order' => 2],
                    ['name' => '柏の葉キャンパス駅', 'sort_order' => 3],
                ],
                '松戸' => [
                    ['name' => '松戸駅', 'sort_order' => 1],
                    ['name' => '新松戸駅', 'sort_order' => 2],
                ],
                '浦安' => [
                    ['name' => '浦安駅', 'sort_order' => 1],
                ],
                '習志野' => [
                    ['name' => '津田沼駅', 'sort_order' => 1],
                    ['name' => '習志野駅', 'sort_order' => 2],
                ],
                '流山' => [
                    ['name' => '流山おおたかの森駅', 'sort_order' => 1],
                    ['name' => '南流山駅', 'sort_order' => 2],
                ],
                '我孫子' => [
                    ['name' => '我孫子駅', 'sort_order' => 1],
                ],
            ],
            14 => [
                'さいたま' => [
                    ['name' => '大宮駅', 'sort_order' => 1],
                    ['name' => '浦和駅', 'sort_order' => 2],
                    ['name' => '北浦和駅', 'sort_order' => 3],
                    ['name' => '南浦和駅', 'sort_order' => 4],
                ],
                '川口' => [
                    ['name' => '川口駅', 'sort_order' => 1],
                    ['name' => '西川口駅', 'sort_order' => 2],
                ],
                '川越' => [
                    ['name' => '川越駅', 'sort_order' => 1],
                    ['name' => '本川越駅', 'sort_order' => 2],
                ],
                '所沢' => [
                    ['name' => '所沢駅', 'sort_order' => 1],
                ],
                '越谷' => [
                    ['name' => '越谷駅', 'sort_order' => 1],
                    ['name' => '新越谷駅', 'sort_order' => 2],
                ],
                '草加' => [
                    ['name' => '草加駅', 'sort_order' => 1],
                ],
                '坂戸' => [
                    ['name' => '坂戸駅', 'sort_order' => 1],
                ],
            ],
        ];
    }
}
