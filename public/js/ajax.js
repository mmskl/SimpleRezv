$(function(){


    $('.free').on('click', function(){

        console.log(this);
        $('#hiddenResv').append('<input class="hiddenRes" type="hidden" value="' + $(this).val() +'">');
        if($(this).prop('class') == 'readyToResv') {
            $(this).prop('class', 'free').text('rezerwuj!');
        } else if($(this).prop('class') == 'free') {
            $(this).prop('class', 'readyToResv').text('odwołaj');
        }

    });


    $('#sendResv').on('click', function(){



        var times = [];
        $('.hiddenRes').each(function(){
            times.push($(this).val());
        });



        $.post(path + 'api/resv.php',
            {rsv : true,
                date : $('#date').text(),
                times : times},
            function(data) {
                console.log(data);
                $('.readyToResv').each(function(){
                    $(this).prop('class', 'taken').text('zajęte');
                    $('#hiddenResv').empty();

                })

            }
        )

    });



});