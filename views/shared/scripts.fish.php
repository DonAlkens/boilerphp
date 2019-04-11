
        <!-- <script>window.jQuery || document.write('<script src="bundles/js/vendor/jquery-1.10.1.min.js"><\/script>')</script> -->
        <script src="../../bundles/js/vendor/jquery-1.10.1.min.js"></script>
        <script src="../../bundles/js/main.min.js"></script>
		<script src="../../bundles/js/reviews.js"></script>        

<script type="text/javascript">
  maxmind_user_id = "88926";
  (function() {
    var loadDeviceJs = function() {
      var element = document.createElement('script');
      element.src = ('https:' == document.location.protocol ? 'https:' : 'http:') 
        + '//device.maxmind.com/js/device.js';
      document.body.appendChild(element);
    };
    if (window.addEventListener) {
      window.addEventListener('load', loadDeviceJs, false);
    } else if (window.attachEvent) {
      window.attachEvent('onload', loadDeviceJs);
    }
  })();
</script> 
<script>
    	var selAddBook = document.querySelector("#addbook");
        selAddBook.onchange = function(){
            var nums = document.querySelector("#txtDestination");
            nums.value = this.value;
        };
    </script>