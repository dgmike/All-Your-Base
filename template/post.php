<?php
$time = niceTime(strtotime(str_replace("+0000", "", $item->created_at)));
?>

        <dl class="<?php if ($_SESSION['access_token']['screen_name'] == $item->user->screen_name) print 'mine' ?>">
            <dt>
                <span class="post_id">#<?php echo $item->id ?></span>
                <strong><a href="http://twitter.com/<?php echo $item->user->screen_name ?>">@<?php echo $item->user->screen_name ?></a></strong>
            </dt>
            <dd class="post_user_image">
                <img src="<?php echo $item->user->profile_image_url ?>" alt="@<?php echo $item->user->screen_name ?>" />
            </dd>
            <dd>
                <span class="post_message_label">Message:</span>
                <?php echo trata_tweet($item->text) ?>
            </dd>
            <dd class="post_source">
                <?php if (isset($item->source)): ?>
                    via <?php echo $item->source ?> ~
                <?php endif ?>

                <a href="http://twitter.com/<?php echo $item->user->screen_name ?>/status/<?php echo $item->id ?>" title="" target="_blank"><?php echo $time ?></a>

                <?php if (isset($item->in_reply_to_screen_name) && $item->in_reply_to_screen_name && $item->in_reply_to_status_id): ?>
                    em resposta a <a href="http://twitter.com/<?php echo $item->in_reply_to_screen_name ?>/status/<?php echo $item->in_reply_to_status_id ?>" target="_blank"><?php echo $item->in_reply_to_screen_name ?></a>
                <?php endif ?>
            </dd>

            <dd class="actions" style="display:none">
                <!-- button class="first">RT</button
                ><button>Quote</button
                ><button>Reply</button
                ><button>Reply to all</button
                ><button>DM</button
                ><button class="last">Fav</button -->
            </dd>
        </dl>
