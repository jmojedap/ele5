    $('#flipbook').turn({
                display: 'double',
                acceleration: true,
                gradients: !$.isTouch,
                elevation:50,
                when: {
                        turned: function(e, page) {
                                /*console.log('Current view: ', $(this).turn('view'));*/
                        }
                }
            });

            $('#notepad').hide(0);

            $('#openNotepad').click(function(){
                    $('#notepad').show(500);
            });

            $('#closeNotepad').click(function(){
                    $('#notepad').hide(500);
            }); 