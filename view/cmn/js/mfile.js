/**
 * Ajaxで複数ファイルをドラッグ＆ドロップでアップロードするためのクラス
 * @see 
 * @see 
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
function MfileFunctions()
{

	/**
	 * クラス内のメソッドから、同じクラスのメソッドやプロパティを参照するための変数
	 */
	var self = this;
	
	/**
	 * ドラッグ＆ドロップされた＝送信する　ファイル数
	 */
	var rowCount = 0;

	/**
	 * アップロード先のURL
	 */
	var uploadURL ="";

	/**
	 * アップロード成功時にコールバックするメソッド
	 */
	var success;

	/**
	 * アップロード失敗時にコールバックするメソッド
	 */
	var failed;

	
	/**
	 * ファイル送信メソッド
	 *
	 * @example self.sendFileToServer(formData, status);
	 * @param object formData 送信するファイルを含んだFormDataオブジェクト
	 * @param object status プログレスバーオブジェクト
	 * @return void
	 */
	this.sendFileToServer = function(formData, status)
	{
		var extraData ={}; //Extra Data.
		var jqXHR=$.ajax({
				xhr: function() {
					var xhrobj = $.ajaxSettings.xhr();
					if (xhrobj.upload) {
						xhrobj.upload.addEventListener('progress', function(event) {
							var percent = 0;
							var position = event.loaded || event.position;
							var total = event.total;
							if (event.lengthComputable) {
								percent = Math.ceil(position / total * 100);
							}
							//Set progress
							status.setProgress(percent);
						}, false);
					}
					return xhrobj;
				},
				url: self.uploadURL,
				type: "POST",
				dataType: "json",
				contentType:false,
				processData: false,
				cache: false,
				data: formData,
				success: function(response, status, xhr){
					if (response.status==true) {
						//status.setProgress(100);
						self.success(response.data);
					} else {
						console.log(response);
						self.faild(response);
					}
				},
		});
		status.setAbort(jqXHR);
	};


	/**
	 * プログレスバーを生成するメソッド
	 * プログレスバーはドラッグエリアDIVの下に表示される
	 *
	 * @example self.createStatusbar(DragAreaJQObj);
	 * @param object DragAreaJQObj サーバのエンドポイント
	 * @return void
	 */
	this.createStatusbar = function(DragAreaJQObj)
	{
		rowCount++;
		var row="odd";
		if(rowCount %2 ==0) row ="even";
		this.statusbar = $("<div class='mfileStatusbar "+row+"'></div>");
		this.filename = $("<div class='mfileFilename'></div>").appendTo(this.statusbar);
		this.size = $("<div class='mfileFilesize'></div>").appendTo(this.statusbar);
		this.progressBar = $("<div class='mfileProgressBar'><div></div></div>").appendTo(this.statusbar);
		this.abort = $("<div class='mfileAbort'>Abort</div>").appendTo(this.statusbar);
		DragAreaJQObj.after(this.statusbar);

		this.setFileNameSize = function(name,size)
		{
			var sizeStr="";
			var sizeKB = size/1024;
			if (parseInt(sizeKB) > 1024) {
				var sizeMB = sizeKB/1024;
				sizeStr = sizeMB.toFixed(2)+" MB";
			} else {
				sizeStr = sizeKB.toFixed(2)+" KB";
			}

			this.filename.html(name);
			this.size.html(sizeStr);
		};
	
		this.setProgress = function(progress)
		{      
			var progressBarWidth =progress*this.progressBar.width()/ 100; 
			this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
			if(parseInt(progress) >= 100) {
				this.abort.hide();
			}
		};
	
		this.setAbort = function(jqxhr) {
			var sb = this.statusbar;
			this.abort.click(function() 
			{
				jqxhr.abort();
				sb.hide();
			});
		};
	};

	/**
	 * 各ファイルをひとつずつ送信するメソッド
	 *
	 * @example self.handleFileUpload(files, DragAreaJQObj);
	 * @param object files ドラッグエリアdivにドロップされたファイル（複数）オブジェクト
	 * @param object DragAreaJQObj ドラッグエリアdivのJQueryオブジェクト
	 * @return void
	 */
	this.handleFileUpload = function(files, DragAreaJQObj)
	{
	   for (var i = 0; i < files.length; i++)
	   {
	        var fd = new FormData();
	        fd.append('file', files[i]);

			// プログレスバーの生成
	        var status = new self.createStatusbar(DragAreaJQObj);
	        status.setFileNameSize(files[i].name,files[i].size);
			
			// ファイルを送信
	        self.sendFileToServer(fd, status);
	   }
	};
	
	/**
	 * 初期化
	 *
	 * @example MFILE.init('dragArea', '/image/mfile', onArea, offArea);
	 * @param string dragAreaDomId ドラッグエリア（ファイルをドラッグ＆ドロップするdiv）のID
	 * @param string fileBtnDomId input type="file" ボタンのID
	 * @param string imageBtnDomId 画像アップロードボタンのID
	 * @param string url アップロード先URL
	 * @param function onCall ファイルがドラッグエリアに入ったときにコールバックするメソッド
	 * @param function offCall ファイルがドラッグエリアから外れたときにコールバックするメソッド
	 * @return void
	 */
	this.init = function(dragAreaDomId, fileBtnDomId, imageBtnDomId, url, onCall, offCall, success, failed)
	{
		self.uploadURL = url;
		self.success = success;
		self.failed = failed;
		
		$(document).ready(function()
		{
			// ファイル選択ボタンの挙動設定
			var FileBottunObj = $('#'+fileBtnDomId);
			var ImageBottunObj = $('#'+imageBtnDomId);

			ImageBottunObj.click(function() {
				// ダミーボタンとinput[type="file"]を連動
				FileBottunObj.click();
			});
 
			FileBottunObj.change(function(){
				// ファイル情報を取得
				var files = this.files;

				// ファイル送信
				self.handleFileUpload(files, DragAreaJQObj);
			});
			

			// ドラッグエリアの挙動設定
			var DragAreaJQObj = $('#'+dragAreaDomId);
			DragAreaJQObj.on('dragenter', function (e)
			{
			    e.stopPropagation();
			    e.preventDefault();
			    //$(this).css('border', '2px solid #0B85A1');
				onCall();
			});
			
			DragAreaJQObj.on('dragover', function (e)
			{
			     e.stopPropagation();
			     e.preventDefault();
			});
			
			DragAreaJQObj.on('drop', function (e)
			{

			     //$(this).css('border', '2px dotted #0B85A1');
				 offCall();
			     e.preventDefault();
			     var files = e.originalEvent.dataTransfer.files;

			     // ファイル送信
			     self.handleFileUpload(files, DragAreaJQObj);
			});

			// ドラッグエリア以外の挙動設定
			$(document).on('dragenter', function (e)
			{
				e.stopPropagation();
				e.preventDefault();
			});
			
			$(document).on('dragover', function (e)
			{
				e.stopPropagation();
				e.preventDefault();
				//obj.css('border', '2px dotted #0B85A1');
				offCall();
			});
			
			$(document).on('drop', function (e)
			{
				e.stopPropagation();
				e.preventDefault();
			});

		});
	};
	

}

var MFILE = new MfileFunctions();

