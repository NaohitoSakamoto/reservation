$(function(){
    /* リンクをクリックした場合 */
    $(".reservationItems p a").click(function(){
        /* ポップアップ表示 */
        $("#jsEditArea").addClass("isShow");

        /* リンク先を取得 */
        var href = $(this).attr('href');

        /* 外部HTMLをロード */
        $("#jsLoadHTML").load(href);
    });

    /* 背景もしくは閉じるボタンが押された場合 */
    $("#jsBackground,#jsCloseBtn").click(function(){
        /* ポップアップ非表示 */
        $("#jsEditArea").removeClass("isShow");
        /* ページリロード */
        window.location.href = "administrator.php";
    });

    return false;
});
