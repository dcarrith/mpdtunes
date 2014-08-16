       @if ($logged_in)

                @include('partials/initJavaScriptVariables')

                @if ($environment === "development")

			<script type="text/javascript" src="/includes/js/moxie.v2.1.1.js"></script>
			<script type="text/javascript" src="/includes/js/plupload.v2.1.1.full.js"></script>
                        <script type="text/javascript" src="/includes/js/jquery.plupload.v2.1.1.queue.js"></script>
			<script type="text/javascript" src="/includes/js/jquery.iframe-transport.js"></script>
			<script type="text/javascript" src="/includes/js/jquery.fileupload.js"></script>
                        <script type="text/javascript" src="/includes/js/mpdtunes.document.ready.js"></script>
                        <script type="text/javascript" src="/includes/js/mpdtunes.mpd.functions.js"></script>
                        <script type="text/javascript" src="/includes/js/mpdtunes.player.functions.js"></script>
			<script type="text/javascript" src="/includes/js/mpdtunes.socket.handlers.js"></script>
			<script type="text/javascript" src="/includes/js/mpdtunes.playlist.functions.js"></script>
                        <script type="text/javascript" src="/includes/js/mpdtunes.queue.functions.js"></script>
                        <script type="text/javascript" src="/includes/js/mpdtunes.navigation.functions.js"></script>
                        <script type="text/javascript" src="/includes/js/mpdtunes.page.listeners.js"></script>

                        <script src="/includes/js/templates/dust/queue.tmpl.js"></script>
			<script src="/includes/js/templates/dust/artists.tmpl.js"></script>
			<script src="/includes/js/templates/dust/albums.tmpl.js"></script>
                        <script src="/includes/js/templates/dust/tracks.tmpl.js"></script>
                        <script src="/includes/js/templates/dust/playlistTracks.tmpl.js"></script>
                @else
                        <script type="text/javascript" src="/includes/js/mpdtunes.cc.min.js"></script>

			<!-- java -jar compiler.jar --compilation_level SIMPLE_OPTIMIZATIONS --js=moxie.v2.1.1.js --js=plupload.v2.1.1.full.js --js=jquery.plupload.v2.1.1.queue.js --js=jquery.iframe-transport.js --js=jquery.fileupload.js --js=mpdtunes.document.ready.js --js=mpdtunes.mpd.functions.js --js=mpdtunes.player.functions.js --js=mpdtunes.socket.handlers.js --js=mpdtunes.playlist.functions.js --js=mpdtunes.queue.functions.js --js=mpdtunes.navigation.functions.js --js=templates/dust/albums.tmpl.js --js=templates/dust/artists.tmpl.js --js=templates/dust/queue.tmpl.js --js=templates/dust/tracks.tmpl.js --js=templates/dust/playlistTracks.tmpl.js --js=mpdtunes.page.listeners.js --create_source_map ./mpdtunes.cc.min.js.map --source_map_format=V3 --js_output_file=mpdtunes.cc.min.js -->

                @endif
	@else
                @include('partials/initJavaScriptVariablesAnonymous')

                @if (isset($paypal_controller))
                        <script type='text/javascript' src='/includes/js/dg.min.js'></script>
                @endif

                <script type="text/javascript" src="/includes/js/mpdtunes.navigation.functions.js"></script>
	@endif
