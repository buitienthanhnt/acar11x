
import { Link } from '@inertiajs/react';
import { ImagePage } from '../Custom';

export function HorizonList(props) {
	if (!props.items) {
		return null;
	}

	return (
		<div className="bg-white p-1 rounded-md">
			<span className="font-semibold text-2xl text-blue-700 lg:py-2">Nội dung đề xuất:</span>
			<div className="w-full overflow-x-auto overscroll-x-contain
				[&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar]:h-2
				[&::-webkit-scrollbar-track]:rounded-full
				[&::-webkit-scrollbar-track]:bg-white
				[&::-webkit-scrollbar-thumb]:rounded-full
				[&::-webkit-scrollbar-thumb]:bg-orange-300
				dark:[&::-webkit-scrollbar-track]:bg-neutral-700
				dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500"
			>
				<div className="flex space-x-2 p-1 w-[270vw] md:w-[160vw]">
					{props.items.map((item, index) => <HorizonItem key={index} page={item}></HorizonItem>)}
				</div>
			</div>
		</div>
	);
}

export function HorizonItem(params) {
	return (
		<Link href={route('detail', { alias: params.page.alias })}
			className='bg-blue-gray-100 w-1/3 md:w-1/4 xl:w-1/5 h-[240px] justify-center items-center flex rounded-md relative'>
			<ImagePage source={params.page.image_path} className='w-full h-full rounded-md'></ImagePage>
			<span className='absolute left-0 right-2 p-1 bottom-0 line-clamp-2 text-md font-semibold text-white bg-[#8d71e863] rounded-sm'>
				{params.page.title}
			</span>
		</Link>
	)
}