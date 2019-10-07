<script>
if (!supportedBrowser()) {
  window.location.replace("/support");
}

function supportedBrowser() {
    browserName = platform.name;
    regex = /\d+/;
    version = platform.version.match(regex)[0];
    console.log(browserName + '' + version);

    switch (browserName) {
      case 'Chrome':
        return version >= 51;
      case 'Firefox':
      case 'Firefox for iOS':
        return version >= 54;
      case 'IE':
      case 'Internet Explorer':
        return false;
      case 'Microsoft Edge':
      case 'Edge':
        return version >= 15;
      case 'Safari':
        return version >= 10;
      case 'Opera':
      case 'Opera Mini':
        return version >= 38;
      default:
        return false;
    }
}
  
</script>