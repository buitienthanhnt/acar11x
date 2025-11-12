import React, { useState } from "react";
import ImagePage from "./ImagePage";
import { PlayCircleIcon } from "@heroicons/react/24/solid";
import YouTube from "react-youtube";
import { Link, } from "@inertiajs/react";

interface Props {
	name: string;
	data: any;
}
const DupVideos = ({ name, data }: Props) => {
	// http://img.youtube.com/vi/XSBQJ3bVJ0U/maxresdefault.jpg
	// aspect-[7/9]: tỷ lệ kích thước chiều cao và chiều ngang của thành phần.
	// absolute center: absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2

	return (
		<div className="bg-white rounded-md">
			<Link className="font-semibold text-2xl text-blue-700 lg:p-2 flex " href="/list" data={{ type: 'video' }}>Video đề Xuất:</Link>
			<div className="p-1 bg-white grid grid-cols-2 gap-4 rounded-md">
				{data.map((item, index) => <VideoBanner item={item} key={`video-${index}`}></VideoBanner>)}
			</div>
		</div>
	)
}

const VideoBanner = ({ item: { id, page_contents, title, alias } }) => {
	const [play, setPlay] = useState(false);

	return (
		<div className="col-span-1 bg-white rounded-md flex relative justify-center items-center" href={route('detail', { alias })}>
			{play ? (
				<div className='w-full aspect-[7/9] rounded-md bg-black'>
					<YouTube style={{ height: '100%' }} videoId={page_contents[0].value} opts={{
						width: '100%',
						height: '100%',
						playerVars: {
							autoplay: 1,
						},
					}} />
				</div>
			) : (
				<ImagePage source={`https://img.youtube.com/vi/${page_contents[0].value}/maxresdefault.jpg`} className='w-full aspect-[7/9] rounded-md'></ImagePage>
			)}
			<p className="absolute bottom-0 left-0 bg-[#925ccc3b] w-full p-1 font-semibold text-xl text-orange-800 rounded-t-md">{title}</p>
			{!play && <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" onClick={() => { setPlay(!play) }}>
				<PlayCircleIcon width={80} height={80} color="red"></PlayCircleIcon>
			</div>}
		</div>
	)
}

export default DupVideos