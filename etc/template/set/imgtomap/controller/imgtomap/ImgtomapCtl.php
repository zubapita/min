<?php
/**
 * Ajax マルチファイル・アップロード コントローラー
 *
 *
 * @copyright    Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author        Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Min - Minimam INter framework for PHP
 * @version    0.1
 *
 * アップロードできない場合は、php.iniの以下の値が足りない可能性がある
 * memory_limit
 * post_max_size
 * upload_max_filesize
 * max_file_uploads
 *
 */
class <!--{$className}-->Ctl extends IndexCtl
{

    /**
     * viewの初期化
     *
     * @return void
     */
    public function __construct()
    {
        $_ = $this;
        parent::__construct();

    }

    /**
     * index アクション
     */
    public function index()
    {
        $_ = $this;

        // viewの表示
        $_->view->display($_->view_template);

    }

    /**
     * post アクション
     *
     * 写真のアップロードを受け取り、リサイズして$imageDir以下に格納
     * 結果を表示する
     * 送信には、mfileUploader.jsを使用する
     */
    public function post()
    {
        $_ = $this;
        $_->initAjax();
        $imageDir = $_->APP_ROOT.'/var/images';
        $subDir = 'uploaded';

        if (!isset($_FILES)) {
            Console::log('No Files.');
            $_->ajax->sendError("No Files");
            return;
        }
        
        foreach ($_FILES as $FILE) {    
            $image = new upload($FILE);
            if ($image->uploaded) {
                $srcPath = $image->file_src_pathname;
                $exif = exif_read_data($srcPath, 0, true);
                $location = $_->getGPS($exif);
                
                $image->image_resize = true;
                $image->image_convert = 'jpg';
                $image->image_x = 200;
                $image->image_ratio_y = true;
                $image->Process("$imageDir/$subDir");
                if ($image->processed) {
                    Console::log('Save image'.$image->file_dst_name.' success.');
                    
                    // viewへの送信（表示）
                    Console::log('Send to browser.');
                    $data = [];
                    $data['filename'] = $subDir.'/'.$image->file_dst_name;
                    $data['width'] = $image->image_dst_x;
                    $data['height'] = $image->image_dst_y;
                    $data['lat'] = $location['lat'];
                    $data['lng'] = $location['lng'];
                    Console::log($data);
                    $_->ajax->sendData($data);
                    
                } else {
                    Console::log('Save image'.$postfix.' failed.');
                }
            } else {
                Console::log('Uploaded image can not handle.');
                Console::log($image->log);
            }
        }
    }



    /**
     * EXIFデータから位置情報を取得する
     * 
     * @param array $exif 
     * @return array array($lat, $lon)
     * 
     */
    public function getGPS($exif)
    {
        $_ = $this;
        
        //緯度を60進数から10進数に変換
        $lat = $_->convert_float($exif['GPS']["GPSLatitude"][0]) + 
                ($_->convert_float($exif['GPS']["GPSLatitude"][1])/60) + 
                ($_->convert_float($exif['GPS']["GPSLatitude"][2])/3600);

        //南緯の場合はマイナスにする
        if ($exif['GPS']["GPSLatitudeRef"]=="S"){
            $lat *= -1;
        }

        //経度を60進数から10進数に変換
        $lng = $_->convert_float($exif['GPS']["GPSLongitude"][0]) + 
                ($_->convert_float($exif['GPS']["GPSLongitude"][1])/60) + 
                ($_->convert_float($exif['GPS']["GPSLongitude"][2])/3600);

        //西経の場合はマイナスにする
        if ($exif['GPS']["GPSLongitudeRef"]=="W"){
            $lng *= -1;
        }

        return array('lat'=>$lat, 'lng'=>$lng);
    }

    //[例:986/100]という文字列を[986÷100=9.86]というように数値に変換する関数
    public function convert_float($str)
    {
        $val = explode("/",$str);
        return (isset($val[1])) ? $val[0] / $val[1] : $str;
    }


}

