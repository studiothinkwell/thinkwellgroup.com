<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<html>
    <head>
        <script src="jquery.js" type="text/javascript"></script>
        <script src="jquery.cross-slide.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function(){

                $(".placeholders").css({
                    position:"relative",
                    overflow:"hidden"
                });

                var intervalID = null;

                $(".placeholders").find("img").each(function(){
                    $(this).css({
                        position:"absolute"
                    });

                    $(this).mouseenter(function(){
                        //start
                        startKenAndBurnEffect(this);
                    }).mouseleave(function(){
                        //stop
                        stopKenAndBurnEffect(this);
                    });
                });

                var left = 0;
                var moveRight = 1;

                function startKenAndBurnEffect(obj)
                {
                    left = 0;
                    intervalID = setInterval(function(){
                        start(obj);
                    }, 20);
                }

                function start(obj)
                {
                    if($(obj).parent().width() + left > 0 && moveRight)
                    {
                        left--;
                        moveRight = 0;
                    }
                    else
                    {
                        left++;
                    }

                    $(obj).css("left",left);
                    
                }

                function stopKenAndBurnEffect(obj)
                {
                    clearInterval(intervalID);
                    //alert("stopped");
                }

                /*$('#palceholders').crossSlide({
                        fade: 1
                    }, [
                    {
                        src:  'images/banner.jpg',
                        from: '100% 80% 1x',
                        to:   '100% 0% 1.7x',
                        time: 3
                    },
                    {
                        src:  'images/banner-2.jpg',
                        from: 'top left',
                        to:   'bottom right 1.5x',
                        time: 2
                    }
                ]);*/
            });
         

        </script>
    </head>
    <body>
        <div class="placeholders" style="height:300px;width: 300px;">
            <img src="images/banner.jpg" />
        </div>
        <div class="placeholders" style="height:300px;width: 300px;">
            <img src="images/banner-2.jpg" />
        </div>
    </body>
</html>