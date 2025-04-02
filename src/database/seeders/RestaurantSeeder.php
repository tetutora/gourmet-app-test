<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;


class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $restaurants = [
            [
                'name' => '仙人',
                'region_id' => 1,
                'genre_id' => 5,
                'description' => '料理長厳選の食材から作る寿司を用いたコースをぜひお楽しみください。食材・味・価格、お客様の満足度を徹底的に追及したお店です。特別な日のお食事、ビジネス接待まで気軽に使用することができます。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg'
            ],
            [
                'name' => '牛助',
                'region_id' => 2,
                'genre_id' => 6,
                'description' => '焼肉業界で20年間経験を積み、肉を熟知したマスターによる実力派焼肉店。長年の実績とお付き合いをもとに、なかなか食べられない希少部位も仕入れております。また、ゆったりとくつろげる空間はお仕事終わりの一杯や女子会にぴったりです。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/yakiniku.jpg'
            ],
            [
                'name' => '戦慄',
                'region_id' => 3,
                'genre_id' => 7,
                'description' => '気軽に立ち寄れる昔懐かしの大衆居酒屋です。キンキンに冷えたビールを、なんと199円で。鳥かわ煮込み串は販売総数100000本突破の名物料理です。仕事帰りに是非御来店ください。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/izakaya.jpg'
            ],
            [
                'name' => 'ルーク',
                'region_id' => 1,
                'genre_id' => 4,
                'description' => '都心にひっそりとたたずむ、古民家を改築した落ち着いた空間です。イタリアで修業を重ねたシェフによるモダンなイタリア料理とソムリエセレクトによる厳選ワインとのペアリングが好評です。ゆっくりと上質な時間をお楽しみください。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/italian.jpg'
            ],
            [
                'name' => '志摩屋',
                'region_id' => 3,
                'genre_id' => 8,
                'description' => 'ラーメン屋とは思えない店内にはカウンター席はもちろん、個室も用意してあります。ラーメンはこってり系・あっさり系ともに揃っています。その他豊富な一品料理やアルコールも用意しており、居酒屋としても利用できます。ぜひご来店をお待ちしております。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/ramen.jpg'
            ],
            [
                'name' => '香',
                'region_id' => 1,
                'genre_id' => 6,
                'description' => '大小さまざまなお部屋をご用意してます。デートや接待、記念日や誕生日など特別な日にご利用ください。皆様のご来店をお待ちしております。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/yakiniku.jpg'
            ],
            [
                'name' => 'JJ',
                'region_id' => 2,
                'genre_id' => 4,
                'description' => 'イタリア製ピザ窯芳ばしく焼き上げた極薄のミラノピッツァや厳選されたワインをお楽しみいただけます。女子会や男子会、記念日やお誕生日会にもオススメです。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/italian.jpg'
            ],
            [
                'name' => 'らーめん極み',
                'region_id' => 1,
                'genre_id' => 8,
                'description' => '一杯、一杯心を込めて職人が作っております。味付けは少し濃いめです。 食べやすく最後の一滴まで美味しく飲めると好評です。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/ramen.jpg'
            ],
            [
                'name' => '鳥雨',
                'region_id' => 2,
                'genre_id' => 7,
                'description' => '素材の旨味を存分に引き出す為に、塩焼を中心としたお店です。比内地鶏を中心に、厳選素材を職人が備長炭で豪快に焼き上げます。清潔な内装に包まれた大人の隠れ家で贅沢で優雅な時間をお過ごし下さい。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/izakaya.jpg'
            ],
            [
                'name' => '築地色合',
                'region_id' => 1,
                'genre_id' => 5,
                'description' => '鮨好きの方の為の鮨屋として、迫力ある大きさの握りを1貫ずつ提供致します。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg'
            ],
            [
                'name' => '晴海',
                'region_id' => 2,
                'genre_id' => 6,
                'description' => '毎年チャンピオン牛を買い付け、仙台市長から表彰されるほどの上質な仕入れをする精肉店オーナーの本当に美味しい国産牛を食べてもらいたいという思いから誕生したお店です。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/yakiniku.jpg'
            ],
            [
                'name' => '三子',
                'region_id' => 3,
                'genre_id' => 6,
                'description' => '最高級の美味しいお肉で日々の疲れを軽減していただければと贅沢にサーロインを盛り込んだ御膳をご用意しております。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/yakiniku.jpg'
            ],
            [
                'name' => '八戒',
                'region_id' => 1,
                'genre_id' => 7,
                'description' => '当店自慢の鍋や焼き鳥などお好きなだけ堪能できる食べ放題プランをご用意しております。飲み放題は2時間と3時間がございます。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/izakaya.jpg'
            ],
            [
                'name' => '福助',
                'region_id' => 2,
                'genre_id' => 5,
                'description' => 'ミシュラン掲載店で磨いた、寿司職人の旨さへのこだわりはもちろん、 食事をゆっくりと楽しんでいただける空間作りも意識し続けております。 接待や大切なお食事にはぜひご利用ください。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg'
            ],
            [
                'name' => 'ラー北',
                'region_id' => 1,
                'genre_id' => 8,
                'description' => 'お昼にはランチを求められるサラリーマン、夕方から夜にかけては、学生や会社帰りのサラリーマン、小上がり席もありファミリー層にも大人気です。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/ramen.jpg'
            ],
            [
                'name' => '銀之月',
                'region_id' => 3,
                'genre_id' => 5,
                'description' => '職人の手仕事により最高の食材を厳選し、季節感を大切にした最高級の寿司を提供いたします。',
                'image_url' => 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg'
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }
    }
}
