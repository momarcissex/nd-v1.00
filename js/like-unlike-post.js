function like(id, pid, posted_by, likes) {
    if($("#"+id).hasClass("fa-heart-o")) {
        $.ajax({
            type: 'POST',
            url: 'post/like.php',
            data: {pid: pid, posted_by: posted_by, type: 'like'},
            success: function (result) {
                $("#"+id).removeClass("fa-heart-o");
                $("#"+id).addClass("fa-heart");
                $("#likes-"+pid).html(result + ' <i class="fa fa-heart aria-hidden="true" style="color:#a8a8a8;"></i>');
            },
            error: function(data) {
                alert("Error 101.");
                alert(data);
            }
        });
    }
    else {
        $.ajax({
            type: 'POST',
            url: 'post/like.php',
            data: {pid: pid, posted_by: posted_by, type: 'unlike'},
            success: function (result) {
                $("#"+id).removeClass("fa-heart");
                $("#"+id).addClass("fa-heart-o");
                $("#likes-"+pid).html(result + ' <i class="fa fa-heart aria-hidden="true" style="color:#a8a8a8;"></i>');
            },
            error: function(data) {
                alert("Error 101.");
                alert(data);
            }
        });
    }
}

function flag(pid, posted_by) {
    var answer =  confirm('Are you sure you want to flag this drop?');
    if (answer == true) {
        $.ajax({
            type: 'POST',
            url: 'post/flag.php',
            data: {pid: pid, posted_by: posted_by},
            error: function() {
                alert('There was an error.');
            }
        });
    }
}