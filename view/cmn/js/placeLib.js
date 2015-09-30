var map, service, point;
var lat = 35.664122;
var lng = 139.729426;
var openFLG=[], overlays=[], iterator=0, current;
var picmaxWidth = 200; //スポット画像の最大幅
var picmaxHeight = 200; //スポット画像の最大高さ

function PlaceLibFunctions()
{
 
	/**
	 * クラス内のメソッドから、同じクラスのメソッドやプロパティを参照するための変数
	 */
	var self = this;
 
	/* 地図初期化 */
	this.initialize = function()
	{ 
		point = new google.maps.LatLng(lat,lng);
		map = new google.maps.Map(document.getElementById('map'), {
			center: point,
			zoom: 18,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			scrollwheel: false /* スクロールホイールによる拡大・縮小無効化 */
		});
		service = new google.maps.places.PlacesService(map);
		self.getPlaceInfo(lat, lng);
	};

	/* オーバーレイ全削除 */
	this.resetOverlay = function(deleteFLG)
	{
		if(overlays.length>0) {
			for (i in overlays) {
				overlays[i][1].close();
				if (deleteFLG==1) {
					openFLG[i] = 0;
					overlays[i][0].setMap(null);
				}
			}
			if(deleteFLG==1) overlays.length = 0;
			if(deleteFLG==1) iterator = 0;
		}
	};

	this.getPlaceInfo = function(lat, lng)
	{
		point = new google.maps.LatLng(lat, lng);
		map.setCenter(point);
		
		self.resetOverlay(1);
		$('#spotList').empty();
		var request = {
			location: point,
			radius:"100", /* 指定した座標から半径100m以内 */
			//types: [$("#places").val()]
			types: ['restaurant', 'food', 'cafe', 'bakery', 'bar', 'grocery_or_supermarket', 'liquor_store', ]
		};
		service.search(request, self.callback);
	};
   
   
	this.callback = function(results, status)
	{
		console.log(results.length);
		if (status==google.maps.places.PlacesServiceStatus.OK && results.length>0) {
			for (var i=0; i<results.length; i++) {
				var place = results[i];
				self.createMarker(results[i]); 
				self.addToList(results[i]); 
				iterator++;
			}
		} else {
			alert("このエリアでの「" + placesTypes[$("#places").val()] + "」に関するスポット情報はありません。");
		}
	};

	this.createMarker = function(place)
	{
		
		var placeLoc = place.geometry.location;
		
		/* マーカー */
		var marker = new google.maps.Marker({
			map: map,
			position: new google.maps.LatLng(placeLoc.lat(), placeLoc.lng())
		});
		
		/* 情報ウィンドウ */
		var infowindow = new google.maps.InfoWindow({
			maxWidth:320
		});
		
		/* ID、フラグセット */
		marker.set("id",iterator);
		infowindow.set("id",iterator);
		overlays.push([marker,infowindow]);
		
		/* 情報ウィンドウの×ボタンと押した時 */
		google.maps.event.addListener(infowindow, "closeclick", function() {
			openFLG[infowindow.get("id")] = 0;
		});
		
		/* マーカークリックで情報ウィンドウを開閉 */
		google.maps.event.addListener(marker, "click", function(){
			var id = this.get("id");
			if (current>=0 && current!=id) {
				openFLG[current] = 0;
			}
			self.resetOverlay(0);
			var s = "";

			/* アイコン+場所名 */
			s += "<div class='ttl cf'>";
			s += (place.icon) ? "<img width='32' height='32' src='" + place.icon + "' style='float:left;margin-right:5px;' />" : "";
			s += (place.name) ? "<b>" + place.name + "</b>" : "不明";
			s += "</div>";
			s += "<div class='detail'>";

		   /* 住所 */
			if (place.vicinity){
				s += "<p>" + place.vicinity + "</p>";
			}

			/* 場所タイプ */
			if (place.types) {
				s += "<p>";
				$.each(place.types, function(x,type) {
					s += (placesTypes[type]) ? placesTypes[type] + "　" : "";
				});
				s += "</p>";
			}

		   /* 営業中か */
		   if (place.opening_hours) {
		       s += "<p>只今営業中！</p>";
		   }
		   
			/* 評価 */
			if (place.rating) {
				s += "<p>評価：" + place.rating + "</p>";
			}

			/* 写真 */
			if (place.photos && place.photos.length>=1) {
			   s += "<p class='picframe'><img src='"
				 + place.photos[0].getUrl({"maxWidth":picmaxWidth,"maxHeight":picmaxHeight})
				 + "' class='shadow size' /></p>";
			}
			current = id;
			var infowindow = overlays[id][1];
			infowindow.setContent("<div class='infowin'>" + s + "</div>");
			if (openFLG[id]!=1) {
				infowindow.open(map, this);
				openFLG[id] = 1;
			} else {
				infowindow.close();
				openFLG[id] = 0;
			}
		});
	};

	this.addToList = function(place)
	{
		var s = "";

		/* アイコン+場所名 */
		s += "<div class='ttl cf'>";
		s += (place.icon) ? "<img width='32' height='32' src='" + place.icon + "' style='float:left;margin-right:5px;' />" : "";
		s += (place.name) ? "<b>" + place.name + "</b>" : "不明";
		s += "</div>";
		s += "<div class='detail'>";

	   /* 住所 */
		if (place.vicinity){
			s += "<p>" + place.vicinity + "</p>";
		}

		s += "<p><a href=\"javascript:dispDetail('"+ place.place_id+"');\">詳細</a></p>";

		var spot = $('<div/>').addClass('spotListItem').html(s);
		$('#spotList').append(spot);
	};

	
}

var PLIB = new PlaceLibFunctions();

PLIB.initialize();

/*
$("#places").bind("change",function(){
    PLIB.getPlaceInfo();
});
*/

/* 現在位置情報を取得 */
/*
$("#btn").click(function(e){
	navigator.geolocation.watchPosition(function(position) {
		point = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		PLIB.getPlaceInfo();
	},null,{enableHighAccuracy:false});
});
*/

/* 営業時間 */
function　getOpenPeriods(place)
{
	var tmp={};
	if(place.opening_hours){
		$.each(place.opening_hours.periods,function(x,openclose){
			var open = (openclose.open && openclose.open.time) ? openclose.open.time : "";
			var close = (openclose.close && openclose.close.time) ? openclose.close.time : "";
			if(!tmp[fChgWeek(openclose.open.nextDate)]){
				tmp[fChgWeek(openclose.open.nextDate)] = new Array();
			}
			tmp[fChgWeek(openclose.open.nextDate)].push([open,close]);
		});

		var tmpmax = aryCount(tmp);

		var s = "<table>";
		for (var i in tmp) {
			if (i==0) {
				//s += "<tr" + ( ( (i)==fGetWeek() ) ? " class='cur'" : "" ) + "><th rowspan='"+tmpmax+"'>営業時間<span>" + ( (place.opening_hours.open_now) ? "営業中！" : "営業時間外" ) + "</span></th>";
			} else {
				s += "<tr"+( ( (i)==fGetWeek() ) ? " class='cur'" : "" ) + ">";
			}

			s += "<td>"+week[i] + "曜日　";
			if (tmp[i]) {
				for (var j in tmp[i]) {
					if (tmp[i][j]) {
						if (tmp[i][j][0]=="0000" && !tmp[i][j][1]) {
							s+="24時間営業";
						} else {
							if (tmp[i][j][0]) {
								s += tmp[i][j][0].substring(0,2)+"時"+tmp[i][j][0].substring(2,4)+"分";
							}
							if (tmp[i][j][1]) {
								if(tmp[i][j][0]) s+="～";
								s+=tmp[i][j][1].substring(0,2)+"時"+tmp[i][j][1].substring(2,4)+"分　";
							}
						}
					}
				}
			}
			s += "</td></tr>";
		}
		s += "</table>";
	}
	return s;
};

var week = {
	"0":"日",
	"1":"月",
	"2":"火",
	"3":"水",
	"4":"木",
	"5":"金",
	"6":"土"
};

//曜日取得
function fGetWeek()
{
	var d = new Date();
	return d.getDay();
}
//曜日に変換
function fChgWeek(EpochSec)
{
	var d = new Date();
	d.setTime(EpochSec);
	return d.getDay();
}
//連想配列カウント
function aryCount(ary){
	var i=0;
	for(key in ary ){ i++; }
	return i;
}

/* プレイスタイプ */
var placesTypes = {
	"accounting":"会計事務所",
	"airport":"空港",
	"amusement_park":"遊園地",
	"aquarium":"水族館",
	"art_gallery":"アート ギャラリー",
	"atm":"ATM",
	"bakery":"ベーカリー、パン屋",
	"bank":"銀行",
	"bar":"居酒屋",
	"beauty_salon":"ビューティー サロン",
	"bicycle_store":"自転車店",
	"book_store":"書店",
	"bowling_alley":"ボウリング場",
	"bus_station":"バスターミナル",
	"cafe":"カフェ",
	"campground":"キャンプ場",
	"car_dealer":"カー ディーラー",
	"car_rental":"レンタカー",
	"car_repair":"車の修理",
	"car_wash":"洗車場",
	"casino":"カジノ",
	"cemetery":"墓地",
	"church":"教会",
	"city_hall":"市役所",
	"clothing_store":"衣料品店",
	"convenience_store":"コンビニエンス ストア",
	"courthouse":"裁判所",
	"dentist":"歯科医",
	"department_store":"百貨店",
	"doctor":"医者",
	"electrician":"電気工",
	"electronics_store":"電器店",
	"embassy":"大使館",
	"establishment":"施設",
	"finance":"金融業",
	"fire_station":"消防署",
	"florist":"花屋",
	"food":"食料品店",
	"funeral_home":"葬儀場",
	"furniture_store":"家具店",
	"gas_station":"ガソリンスタンド",
	"general_contractor":"建設会社",
	"geocode":"ジオコード",
	"grocery_or_supermarket":"スーパー",
	"gym":"スポーツクラブ",
	"hair_care":"ヘアケア",
	"hardware_store":"金物店",
	"health":"健康",
	"hindu_temple":"ヒンドゥー寺院",
	"home_goods_store":"インテリア ショップ",
	"hospital":"病院",
	"insurance_agency":"保険代理店",
	"jewelry_store":"宝飾店",
	"laundry":"クリーニング店",
	"lawyer":"弁護士",
	"library":"図書館",
	"liquor_store":"酒店",
	"local_government_office":"役場",
	"locksmith":"錠屋",
	"lodging":"宿泊施設",
	"meal_delivery":"出前",
	"meal_takeaway":"テイクアウト",
	"mosque":"モスク",
	"movie_rental":"DVD レンタル",
	"movie_theater":"映画館",
	"moving_company":"引越会社",
	"museum":"美術館/博物館",
	"night_club":"ナイト クラブ",
	"painter":"塗装業",
	"park":"公園",
	"parking":"駐車場",
	"pet_store":"ペット ショップ",
	"pharmacy":"薬局",
	"physiotherapist":"理学療法士",
	"place_of_worship":"礼拝所",
	"plumber":"配管工",
	"police":"警察",
	"post_office":"郵便局",
	"real_estate_agency":"不動産業",
	"restaurant":"レストラン",
	"roofing_contractor":"防水工事業",
	"rv_park":"オート キャンプ場",
	"school":"学校",
	"shoe_store":"靴屋",
	"shopping_mall":"ショッピング モール",
	"spa":"温泉、スパ",
	"stadium":"スタジアム",
	"storage":"倉庫",
	"store":"小売店",
	"subway_station":"地下鉄駅",
	"synagogue":"シナゴーグ",
	"taxi_stand":"タクシー乗り場",
	"train_station":"駅",
	"travel_agency":"旅行代理店",
	"university":"大学",
	"veterinary_care":"獣医",
	"zoo":"動物園",
	"administrative_area_level_1":"行政区画レベル 1",
	"administrative_area_level_2":"行政区画レベル 2",
	"administrative_area_level_3":"行政区画レベル 3",
	"colloquial_area":"非公式地域",
	"country":"国",
	"floor":"階",
	"intersection":"交差点",
	"locality":"地区",
	"natural_feature":"地勢",
	"neighborhood":"周辺地域",
	"political":"政治",
	"point_of_interest":"スポット",
	"post_box":"ポスト",
	"postal_code":"郵便番号",
	"postal_code_prefix":"郵便番号のプレフィックス",
	"postal_town":"郵便番号に対応する都市",
	"premise":"建物名",
	"room":"部屋",
	"route":"ルート",
	"street_address":"住所",
	"street_number":"番地",
	"sublocality":"下位地区",
	"sublocality_level_4":"下位地区レベル 4",
	"sublocality_level_5":"下位地区レベル 5",
	"sublocality_level_3":"下位地区レベル 3",
	"sublocality_level_2":"下位地区レベル 2",
	"sublocality_level_1":"下位地区レベル 1",
	"subpremise":"区画",
	"transit_station":"駅、停留所"
};


