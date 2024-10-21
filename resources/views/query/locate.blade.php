<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <link href="https://js.radar.com/v4.4.3/radar.css" rel="stylesheet">
    <script src="https://js.radar.com/v4.4.3/radar.min.js"></script>
  </head>

  <body>
    {{-- <div id="autocomplete"/> --}}

    <input type="text" id="autocomplete">

    <script type="text/javascript">
      Radar.initialize('prj_live_pk_282bce66618b63742b0ad59cd6a9c2deda92aadd');

      // create autocomplete input
      Radar.ui.autocomplete({
        container: 'autocomplete',
        showMarkers: true,
        markerColor: '#ACBDC8',
        responsive: true,
        width: '600px',
        maxHeight: '600px',
        placeholder: 'Search address',
        limit: 8,
        minCharacters: 3,
        // omit near to use default IP address location
        near: null,
        onSelection: (address) => {
          console.log(address);

        },
      });
    </script>
  </body>
</html>
