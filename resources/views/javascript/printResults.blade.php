<script>
    function printResults(){
        try {
            var toPrint = document.getElementById('testpart'); 
            document.getElementById('testpart').innerHTML = document.getElementById('printfinished').innerHTML + document.getElementById('testpart').innerHTML;
            toPrint.style.display = 'inline';
            printJS('testpart', 'html'); 
            toPrint.style.display = 'none';
        } catch (err) {
            console.log(err);
        }
    }       
</script>