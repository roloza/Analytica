var timeHtmlDomReady;
var id;
var doAjaxBeforeUnloadEnabled = true;
var   timeInterval = 1000;
var statUrl = 'https://tracker.analytica.tk/php/stats.php';

$(document).ready(function() {
  id =  Date.now();
  timeHtmlDomReady = Date.now()-timerStart;

  var w = window.innerWidth
	|| document.documentElement.clientWidth
	|| document.body.clientWidth;

	var h = window.innerHeight
	|| document.documentElement.clientHeight
	|| document.body.clientHeight;
  $.post(statUrl, {
    status : 'ready',
    id : id,
    timeHtmlDomReady:	timeHtmlDomReady,
    url: 							location.protocol + '//' + location.hostname + location.pathname + location.search,
    referer:					document.referrer,
    userAgent: 				window.navigator.userAgent,
    resolution: 			screen.width + 'x' + screen.height,
    resolutionWindow:	w + 'x' + h
   });

   /* Flag fin de visite page : quitte la page ou clic sur un lien */
   window.onbeforeunload = doAjaxBeforeUnload;
   $(window).unload(doAjaxBeforeUnload);
   $('a').click(doAjaxBeforeUnload);
});

$(window).load(function() {
	 $.post(statUrl, {
      status : 'load',
      id: id,
      timeHtmlLoad : 		Date.now()-timerStart
		});
});

var doAjaxBeforeUnload = function (evt) {
    if (!doAjaxBeforeUnloadEnabled) {
        return;
    }
    doAjaxBeforeUnloadEnabled = false;
    postAjaxTimeVisite();
}
var count = 0;
var interval = setInterval(function() {
  count++;
  if (count == 1 || count == 5 || count == 10 || count == 30 || count == 60) {
    postAjaxTimeVisite();
  }
}, 1000);

function postAjaxTimeVisite() {
  $.post(statUrl, {
    status: 'exit',
    id: id,
    timeVisiteUrl : 		Date.now()-timerStart
  });
}
