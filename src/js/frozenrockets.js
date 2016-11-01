  function loadCSS( href, media ){
    "use strict";

    var ss = window.document.createElement( "link" );
    var ref = window.document.getElementsByTagName( "head" )[ 0 ];
    var sheets = window.document.styleSheets;
    ss.rel = "stylesheet";
    ss.href = href;
    ss.media = "only x";

    // inject link
    ref.appendChild( ss, ref );

    function toggleMedia(){
      var defined;
      for( var i = 0; i < sheets.length; i++ ){
        if( sheets[ i ].href && sheets[ i ].href.indexOf( ss.href ) > -1 ){
          defined = true;
        }
      }
      if( defined ){
        ss.media = media || "all";
      }
      else {
        setTimeout( toggleMedia );
      }
    }
    toggleMedia();
    return ss;
  }


  loadCSS("//cloud.typography.com/7599872/689164/css/fonts.css", "all");