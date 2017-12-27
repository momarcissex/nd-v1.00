        <div class="chat_box">
            <div class="chat_head">
                <p>Inbox</p>
                <button class="message_button">New Message</button>
            </div>
            <div class="chat_body">

                </div>
            </div>
        </div>

        <div class="message"></div>

<script type="text/javascript">
    var renewBody;
    function updateBody() {
        //console.log('call updateBody');
        $.ajax({
            type: 'POST',
            url: 'inc/update_msg_body.php',
            success: function(data) {
                $('.chat_body').html(data);
            },
            complete: function() {
                renewBody = setTimeout(function() {
                    updateBody();
                }, 10000);
            }
        });
    }
    updateBody();
</script>