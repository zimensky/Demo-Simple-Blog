<!DOCTYPE html>
<html lang="ru">
<head>
    <base href="<?php echo SITE_URL; ?>" />
    <meta charset="utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <link type="text/css" rel="stylesheet" href="/app/css/default.css" />
    <link type="text/css" rel="stylesheet" href="/app/css/paginate.css" />
    <script type="text/javascript" src="/app/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/app/js/tiny_mce/tiny_mce.js"></script>

    <script>
        tinyMCE.init({
            // General options
            mode : "textareas",
            theme : "advanced",
            plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,

            // Skin options
            skin : "o2k7",
            skin_variant : "silver",

            // Example content CSS (should be your site CSS)
            content_css : "css/example.css",

            // Drop lists for link/image/media/template dialogs
            template_external_list_url : "js/template_list.js",
            external_link_list_url : "js/link_list.js",
            external_image_list_url : "js/image_list.js",
            media_external_list_url : "js/media_list.js",

            // Replace values for the template plugin
            template_replace_values : {
                username : "Some User",
                staffid : "991234"
            }
        });
    </script>
</head>
<body>
<div id="wrapper">

    <?php include_once ROOT.'/app/includes/header.inc.php'; ?>

    <div class="cleaner">

        <div id="left">
            <div id="profile" class="inbar"><?php include_once ROOT.'/app/includes/profile.inc.php';  ?></div>
            <div id="side-menu" class="inbar"><?php include_once ROOT.'/app/includes/userMenu.inc.php';  ?></div>
        </div>

        <div id="right">
            <div id="main-content"><?php echo $mainContent; ?></div>
        </div>

    </div>

    <?php include_once ROOT.'/app/includes/footer.inc.php'; ?>

</div>
</body>
</html>