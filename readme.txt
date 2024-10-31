=== NW Company Profile ===
Contributors: NAKWEB
Tags: profile, company, basic, information, nakweb, nw
Requires at least: 4.9.13
Tested up to: 5.4.2
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 7.0

== Description ==

NW Company Profile はウェブサイト内で汎用的に利用する基本情報を管理できるプラグインです。

= How to get options ? =

**IDを指定して設定値を取得する**

`NWPF::get('sample01')`

**IDを指定してラベルを取得する**

`NWPF::get_label('sample01')`

**IDを指定してラベルと設定値の配列を取得する**

`NWPF::gets('sample01')`

**すべてのオプションのラベルと設定値の連想配列を取得する**

※設定値が空のオプションは取得しません。

`NWPF::gets()`

**パラメータを設定して複数のオプションのラベルと設定値の連想配列を取得する**

※設定値が空のオプションは取得しません。

`NWPF::gets( array( 'key' => array( 'sample01', 'sample02'), 'exclude' => false )`

* key - 配列にて複数のスラッグを指定します。[exclude]パラメータの真偽値に従って取得する配列が異なります。（初期値：NULL）
* exclude - trueを指定すると[key]パラメータに含まれるスラッグを取得対象から除外します。（初期値：false）

**グループを指定してラベルと複数のオプションのラベルと設定値の連想配列を取得する**

`NWPF::get_group('group_name')`

**住所情報を連結して出力する**

※設定値が空のオプションは取得しません。

`NWPF::get_address()`

任意の位置に範克スペースを挿入する場合は引数を指定します。

`NWPF::get_address('sp1' => false, 'sp2' => true, 'sp3' => true)`

* sp1 - "zip"に設定値がある場合、その直後に半角スペースを挿入します。（初期値：true）
* sp2 - "address1"に設定値がある場合、その直前に半角スペースを挿入します。（初期値：false）
* sp3 - "address2"に設定値がある場合、その直前に半角スペースを挿入します。（初期値：false）

= How to output options ? =

**配列の出力**

    $option = NWPF::gets('sample01');
    echo 'Label : ` . $option[0] . '<br />' . 'Value : ' . $option[1];

**連想配列の出力**

    $options = NWPF::gets();
    foreach( $options as $option ) :
        echo 'Label : ` . $option[0] . '<br />' . 'Value : ' . $option[1];
    endforeach;

**本プラグインに定められた形式でＨＴＭＬを出力する**

`NWPF::display()`

* key - 配列にて複数のスラッグを指定します。[exclude]パラメータの真偽値に従って取得する配列が異なります。（初期値：NULL）
* exclude - trueを指定すると[key]パラメータに含まれるスラッグを取得対象から除外します。（初期値：false）
* address_label - 連結された住所情報が出力される場合、その項目のラベルを指定できます。（初期値：'所在地'）
* sp1 - "zip"に設定値がある場合、その直後に半角スペースを挿入します。（初期値：true）
* sp2 - "address1"に設定値がある場合、その直前に半角スペースを挿入します。（初期値：false）
* sp3 - "address2"に設定値がある場合、その直前に半角スペースを挿入します。（初期値：false）


* NW系プラグインについて
     * 本プラグイン以外にもNW系として複数のプラグインを作成しています。
     * すべてのNW系プラグインの設定画面は同一のトップレベルメニューのサブメニューとして追加されます。
     * トップレベルメニューのラベルはプラグイン上で変更が可能です。


== Installation ==

1. From the WP admin panel, click “Plugins” -> “Add new”.
2. In the browser input box, type “NW Company Profile”.
3. Select the “NW Company Profile” plugin and click “Install”.
4. Activate the plugin.

OR…

1. Download the plugin from this page.
2. Save the .zip file to a location on your computer.
3. Open the WP admin panel, and click “Plugins” -> “Add new”.
4. Click “upload”.. then browse to the .zip file downloaded from this page.
5. Click “Install”.. and then “Activate plugin”.

== Changelog ==
= 1.0.2 =
* 軽微修正

= 1.0.1 =
* PHP 8.0 対応

= 1.0.0 =
* Initial release.
