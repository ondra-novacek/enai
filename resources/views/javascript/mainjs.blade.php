<script type="text/javascript">
window.onload = function() {

    // experiment, function to change text to input after click
    var header = document.getElementById('surveyname');

    if (header.contentEditable) {
            header.onblur = function() {
                let text = header.innerHTML;
                console.log(text);
            }

    }
}
</script>