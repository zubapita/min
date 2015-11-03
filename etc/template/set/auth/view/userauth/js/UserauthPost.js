/**
 * userauth のAjaxデータ送信
 * @see lib/AjaxCtl.php
 * @see view/cmn/js/ajax.js
 *
 * @copyright    Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author        Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package    Min - Minimam INter framework for PHP
 * @version    0.1
 */
function UserauthPost()
{
    /**
     * クラス内部から自分自身を参照するための変数
     */
    var self = this;

    /**
     * ログインフォームの内容をサーバに送信
     * 
     * @param 
     * @return boolean 常にfalse
     */
    this.auth = function ()
    {
        var data = {
            username: $('#UserauthUsername').val(),
            password: $('#UserauthPassword').val(),
        };
        var url = '/userauth/auth';
        var success = function(data) {
            if (data.auth) {
                if (data.referer) {
                    if (data.referer.slice(-6)=='logout') {
                        location.href = '/';
                    } else {
                        location.href = data.referer;
                    }
                } else {
                    location.href = '/';
                }
            } else {
                alert('Login failed.');
            }
        }
        AJAX.post(url, data, success);
    
        return false;
    };

    /**
     * AJAXで指定されたページにForm内のデータを送信する
     * 
     * @param 
     * @return boolean 常にfalse
     */
    this.save = function ()
    {
        var data = {
            id: $('#UserauthId').val(),
            username: $('#UserauthUsername').val(),
            password: $('#UserauthPassword').val(),
            email: $('#UserauthEmail').val(),
            entryAt: $('#UserauthEntryAt').val(),
            updateAt: $('#UserauthUpdateAt').val(),
            token: $('#Session-token').val(),
        };
        var url = '/userauth/save';
        var success = function(data) {
            var id = data.id;
            if (id) {
                if (data.oauth=='yes') {
                    location.href = '/oauth/continueRegist?id='+id;
                } else {
                    location.href = '/userauth/?id='+id;
                }
            } else {
                alert(data.message);
            }
        }
        AJAX.post(url, data, success);
    
        return false;
    };
    
    /**
     * 初期化
     */
    this.init = function ()
    {
        $(document).ready(function()
        {

            // ボタン動作設定
            var loginButtonDomId = '#UserauthLoginButton';
            $(loginButtonDomId).on('click', function ()
            {
                self.auth();
            });

            var editButtonDomId = '#UserauthEditButton';
            $(editButtonDomId).on('click', function ()
            {
                var GET = AJAX.getGETParam();
                location.href='/userauth/edit?id='+GET['id'];
            });

            if ($('#UserauthReturnButton')[0]) {
                var returnButtonDomId = '#UserauthReturnButton';
                $(returnButtonDomId).on('click', function ()
                {
                    history.back();
                });
            }

            var saveButtonDomId = '#UserauthSaveButton';
            $(saveButtonDomId).on('click', function ()
            {
                $('#UserauthForm').submit();
            });

            // バリデートしてOKなら保存
            if ($('#UserauthForm')[0]) {
                $("#UserauthForm").validationEngine('attach', {
                    onFieldSuccess: function(field) {
                        $(field).parent().parent().removeClass('has-error');
                        $(field).parent().parent().addClass('has-success');
                        $(field).next().removeClass('glyphicon-remove');
                        $(field).next().addClass('glyphicon glyphicon-ok');
                    },
                    onFieldFailure: function(field) {
                        $(field).parent().parent().removeClass('has-success');
                        $(field).parent().parent().addClass('has-error');
                        $(field).next().removeClass('glyphicon-ok');
                        $(field).next().addClass('glyphicon glyphicon-remove');
                    },
                    onValidationComplete: function(form, status){
                        if (status) {
                          self.save();
                        }
                    }  
                });
            }


        });
    }
    
} /* end of UserauthPost */

var UserauthPost = new UserauthPost();

UserauthPost.init();