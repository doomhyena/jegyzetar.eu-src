<input type="text" placeholder="Címke keresése..." id="kereso" name="tags" class="input" value="">

<div id="talalatok"></div>

<style>
#tag {
    height: auto;
    border: none;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    
}

    .tag {
        padding: 6px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    #talalatok {
        max-height: 200px;
        overflow: auto;
        margin-top: 4px;
    }
</style>

<script>
    $(function() {
        function loadSuggestions(val) {
            $("#talalatok").load("assets/php/findtag.php?keresett=" + encodeURIComponent(val));
        }

        $('#kereso').on('keyup', function(e) {
            var ertek = e.target.value;
            loadSuggestions(ertek);
        });

        $(document).on('click', '.tag', function() {
            var tag = $(this).data('tag');

            var currentValue = $('#tag').val().trim();
            if (currentValue) {
                $('#tag').val(currentValue + ', ' + tag);
            } else {
                $('#tag').val(tag);
            }
        });
        if ($('#kereso').val().trim() !== '') loadSuggestions($('#kereso').val());
    });
</script>