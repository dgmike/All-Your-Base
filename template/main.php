<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charst="utf-8" />
    <title>All Your Base</title>
    <!--[if IE]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <style>
        * {margin:0;padding:0;list-style:none}
        html{position:relative;height:100%;width:100%;}
        body{font-family:'Helvetica Neue', HelveticaNeue, Helvetica-Neue, Helvetica, sans-serif;position:relative;height:100%;width:100%;overflow:hidden;}
        aside, nav, section, article, header, footer {display:block}

        #menu {background:#EAEAEA;position:absolute;height:100%;width:150px;overflow:hidden;-webkit-box-shadow:0px 0px 10px #888;z-Index:1}
        #menu h1 {background:#DDD;position:relative;top:0;font-size:12px;padding:10px;text-align:center;-webkit-box-shadow:0px 0px 5px #888;}
        #menu h2 {font-size:12px;font-weight:normal;padding:10px 10px 5px 10px;border-bottom:1px solid #DDD;}
        #menu li {font-size:12px;font-weight:normal;padding:5px 5px 5px 20px;border-bottom:1px solid #DDD;border-top:1px solid #FFF;display:none;}
        #menu h2:hover, #menu li:hover {cursor:pointer;background:#DFDFDF;}
        #menu .actual {background: -webkit-gradient(linear, 0 40%, 0 70%, from(#3399FF), to(#0066FF));color:#FFF;text-shadow: rgba(0, 0, 0, 0.496094) 0px 1px 0px;}
        #menu .actual:hover {background: -webkit-gradient(linear, 0 40%, 0 70%, from(#3399FF), to(#0066FF));color:#FFF;}

        #content {background:#F2F3F3;position:relative;max-width:100%;margin-left:150px;top:0;left:0;height:100%;}
        #content .wrap {overflow:auto;position:absolute;top:10px;right:10px;left:10px;bottom:100px;background:#FFF;padding:10px;-webkit-border-radius:3px;}
        #content .wrap hr {margin:5px 0;}
        #content > .post {position:absolute;bottom:10px;left:10px;right:10px;height:70px;border:1px solid #DDD;-webkit-border-radius:3px;padding:5px;}
        #content > .post textarea {border:1px solid #CCC;-webkit-border-radius:3px;position:absolute;left:5px;top:5px;right:70px;height:30px;padding:5px 60px 5px 5px;overflow:hidden;}
        #content > .post .faltam {position:absolute;color:#D5D5D5;right:75px;font-size:37px;font-weight:600;}
        #content > .post button {position:relative;border:1px solid #888;background:-webkit-gradient(linear, 0 40%, 0 70%, from(#F9F9F9), to(#E3E3E3));}
        #content > .post .send {position:absolute;font-weight:600;right:5px;top:5px;width:60px;height:41px;-webkit-border-radius:3px;}
        #content > .post .send:hover {background:-webkit-gradient(linear, 0 40%, 0 70%, from(#FFFFCC), to(#FFFF99));}
        #content > .post aside button {top:50px;font-size:11px;padding:2px 5px;border-right-width:0;}

        #content dl {border:1px solid #F5F5F5;font-size:12px;padding:5px;min-height:55px;background:-webkit-gradient(linear, 0 80%, 0 90%, from(#FEFEFE), to(#FCFCFC));border-radius:3px;margin-bottom:2px;position:relative;}
        #content dl.mine {background:-webkit-gradient(linear, 0 80%, 0 90%, from(#DDFFDD), to(#CCFFCC));}
        #content dt, #content dd {display:inline;}
        #content .post_user_image img {width:48px;height:48px;background:#FFF;float:left;margin:0;margin-right:5px;padding:3px;border:1px solid #EEE;border-radius:3px;}
        .post_id, .post_message_label {display:none;}

        #content .wrap .post_source {position:absolute;bottom:5px;left:65px;color:#AAA;}
        #content .wrap .post_source a {color:#AAA;}

        #content .wrap .actions {display:block;position:absolute;bottom:5px;right:5px;}
        #content .wrap .actions button {padding:1px 3px;position:relative;border:1px solid #888;border-right-width:0;background:-webkit-gradient(linear, 0 40%, 0 70%, from(#F9F9F9), to(#E3E3E3));}

        #content .wrap button.first, #content > .post aside button.first {-webkit-border-top-left-radius:3px 3px;-webkit-border-bottom-left-radius:3px 3px;}
        #content .wrap button.last, #content > .post aside button.last {-webkit-border-top-right-radius:3px 3px;-webkit-border-bottom-right-radius:3px 3px;border-right-width:1px;}
        #content .wrap button:hover, #content > .post aside button:hover {-webkit-box-shadow:0 0 6px #666;z-Index:2}
    </style>

    <script type="text/javascript" language="JavaScript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
    <script type="text/javascript" language="JavaScript">
        $(function(){
            $('#menu h2').click(function(){

                $(this).parent().find('li').slideToggle()
                $('#menu h2').not(this).parent().find('li:visible').slideUp()
                $('#menu h2').not(this).find('span').html('►')

                if ( $(this).parent().find('li:visible').size() ) {
                    $(this).find('span').html('▼')
                } else {
                    $(this).find('span').html('►')
                }
            })

            $('#menu li').click(function(){
                $('#menu li').removeClass('actual')
                $(this).addClass('actual')
                $('#content .wrap').html('')
                update()
            })

            $('dl').live('mouseover mouseout', function(){
                if (event.type == 'mouseover') {
                    $(this).css('background', '-webkit-gradient(linear, 0 80%, 0 90%, from(#FFFFEE), to(#FFFFDD))')
                    $(this).css('-webkit-box-shadow', '0 0 3px #666')
                    $(this).find('.actions').show()
                } else {
                    if ($(this).hasClass('mine')) {
                        $(this).css('background', '-webkit-gradient(linear, 0 80%, 0 90%, from(#DDFFDD), to(#CCFFCC))')
                    } else {
                        $(this).css('background', '-webkit-gradient(linear, 0 80%, 0 90%, from(#FEFEFE), to(#FCFCFC))')
                    }

                    $(this).css('-webkit-box-shadow', '0 0 0')
                    $(this).find('.actions').hide()
                }
            })

            $('#menu h2:first').click()

            $('.send').click(sendMessage)

            $('#message').keyup(updateChars)

            setInterval(update, 30000)
        })

        function sendMessage() {
            var msg = $.trim($('#message').val())
            if (!msg) return false
            $('#message').val('sending...').attr('disabled', 'disabled')
            $('.faltam').text('')
            $.post('/post', {msg:msg}, function(data) {
                $('#message').val('').removeAttr('disabled')
                updateChars()
            })
        }

        function update() {
            var since = 0
            if ($('.post_id:first').size()) {
                since = $('.post_id:first').text().substr(1)
            }
            var data = {
                use: $('#menu .actual').attr('use'),
                since: since
            }
            $.post('/update', data, function (data) {
                if (data) {
                    $('#content .wrap hr').remove()
                    $('#content .wrap').prepend('<hr />')
                    $('#content .wrap').prepend(data)
                }
            })
        }

        function updateChars(){
            var length = parseInt($('#message').val().length, 10)
            var val = 140 - length
            $('.faltam').text(val)
            if (val < 0) {
                $('#message').css('background', '#FFDDDD')
            } else {
                $('#message').css('background', '#FFFFFF')
            }
        }

    </script>
</head>
<body>

<nav id="menu">
    <h1>All Your Base Are Bellong To Us</h1>
    <ul>
        <h2><span>►</span> Inbox</h2>

        <li class="actual" use="statuses/home_timeline">Home</li>
        <li use="statuses/mentions">Replies</li>
        <li use="favorites">Favorites</li>
    </ul>
    <ul>
        <h2><span>►</span> Retweets</h2>
        <li use="statuses/retweeted_by_me">By me</li>
        <li use="statuses/retweeted_to_me">To me</li>
    </ul>
    <ul>
        <h2><span>►</span> Directs</h2>
        <li use="direct_messages">Recived</li>
        <li use="direct_messages/sent">Sent</li>
    </ul>
    <ul>
        <h2><span>►</span> Lists</h2>
        <?php foreach($lists->lists as $list): ?>
        <li use="<?php echo $_SESSION['access_token']['screen_name'] ?>/lists/<?php echo $list->id ?>/statuses">
            <?php if ($list->mode == 'private'): ?><img src="/static/lock.png" alt="Private" /><?php endif ?>
            <?php echo $list->name ?>
        </li>
        <?php endforeach ?>
    </ul>
    <!-- ul>
        <h2><span>►</span> Trends</h2>
        <li>amigos</li>
    </ul>
    <ul>
        <h2><span>►</span> Search</h2>
        <li>amigos</li>
    </ul -->
</nav>

<section id="content">
    <div class="wrap">
        <?php foreach($timeline as $item) include dirname(__FILE__).'/post.php'; ?>
        <?php print "<!-- pre>".print_r($timeline,true)."</pre-->"; ?>
    </div>

    <div class="post">
        <textarea id="message"></textarea>
        <span class="faltam">140</span>
        <button class="send" onclick="return false;">Enviar</button>
        <!--aside class="actions">
            <button class="twitpic first">Twitpic</button
            ><button class="yfrog last">yFrog</button>

            <button class="bitly first">Bit.ly</button
            ><button class="migreme last">Migre.me</button>

            <button class="utf-8table first last">UTF-8 table</button>

            <button class="desproxilador first last">Desproxilador</button>
        </aside-->
    </div>

</section>

</body>
</html>