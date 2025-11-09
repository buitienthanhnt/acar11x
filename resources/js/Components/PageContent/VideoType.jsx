import { useCallback, useMemo, useState } from "react";
import YouTube from "react-youtube";

export default function VideoType(params) {
	const [height, setHeight] = useState();

	const opts = useMemo(() => {
		return {
			width: '100%',
			height: '100%',
			playerVars: {
				// https://developers.google.com/youtube/player_parameters
				autoplay: 1,
			},
		}
	}, [height]);

	const onPlayerReady = useCallback((event) => {
		// access to player in all event handlers via event.target
		// height = windownH - topNavHeight - paddingY
		const pageContentHeight = window.innerHeight -72 - 16;
		const tyleman = window.innerWidth/window.innerHeight;

		setHeight(tyleman > 1 ? pageContentHeight : event.target.getSize().width / 9 * 15);
		// event.target.pauseVideo();
	}, [])

	if (!params.content.value) {
		return null;
	}

	return (
		<div className="flex justify-center items-center p-1 lg:px-4 content-video">
			<div className="w-full lg:w-2/3 xl:w-1/2 aspect-[6/9] bg-green">
				<YouTube videoId={params.content.value} opts={opts} style={{height: '100%'}}/>
			</div>
		</div>
	)
}