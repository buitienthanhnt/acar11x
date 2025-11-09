import { stringConvert } from '@/Helper/DateTime';
import { HeartIcon, StarIcon, FireIcon, HandThumbUpIcon, ChatBubbleOvalLeftEllipsisIcon } from '@heroicons/react/24/solid';
import { Link } from '@inertiajs/react';
import { useCallback } from 'react';

const ListItem = ({ item: { id, title, desciption, image_path, alias, updated_at, source, comments_count } }) => {
	return (
		<div
			className="scale-100 p-6 bg-white hover:ring-1 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500"
		>
			<div className="flex-1">
				<div className='flex space-x-3'>
					<div className="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
						<img src={image_path} alt="" className="w-full h-full rounded-full object-cover" title={title} />
					</div>
					<ListItemInfo source={source} updated_at={updated_at} comments_count={comments_count}></ListItemInfo>
				</div>
				<Link href={route('detail', { alias: alias })} // data={{page:1, size: 8}}
				>
					<h2 className="mt-6 text-xl font-semibold text-gray-900 dark:text-white line-clamp-2">
						{title}
					</h2>
					{/* show html content in react element */}
					<p className="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3" dangerouslySetInnerHTML={{ __html: desciption }} />
				</Link>
			</div>
			<Link href={route('detail', { alias: alias })} className="self-center">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					fill="none"
					viewBox="0 0 24 24"
					strokeWidth="1.5"
					className="shrink-0 stroke-red-500 w-6 h-6"
				>
					<path
						strokeLinecap="round"
						strokeLinejoin="round"
						d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
					/>
				</svg></Link>
		</div>
	)
}

export const ListItemInfo = ({source, updated_at, comments_count, className}) => {
	const addViewd = useCallback(() => {
		return;
		const viewed = localStorage.getItem('viewed')?.split('|') || [];
		localStorage.setItem('viewed', [...viewed, id].join('|'));
	}, [])
	
	return (
		<div className={`flex flex-1 flex-col items-start justify-end ${className}`}>
			<span className='text-deep-purple-400'>{stringConvert(updated_at)}</span>
			<div className='flex gap-x-2'>
				{source && Object.keys(source?.value).map((k, index) => {
					switch (k) {
						case 'like':
							return (
								<div key={index} className='flex space-x-1'>
									<span className='font-extrabold'>{source.value?.[k]}</span>
									<HandThumbUpIcon color='#007aff' width={20} height={20} onClick={addViewd}> </HandThumbUpIcon>
								</div>
							)
						case 'heart':
							return (
								<div key={index} className='flex space-x-1'>
									<span className='font-extrabold'>{source.value?.[k]}</span>
									<HeartIcon color='red' width={20} height={20} onClick={addViewd}></HeartIcon>
								</div>
							)
						case 'star':
							return (
								<div key={index} className='flex space-x-1'>
									<span className='font-extrabold'>{source.value?.[k]}</span>
									<StarIcon color='yellow' width={20} height={20} onClick={addViewd}></StarIcon>
								</div>
							)
						case 'fire':
							return (
								<div key={index} className='flex space-x-1'>
									<span className='font-extrabold'>{source.value?.[k]}</span>
									<FireIcon color='red' width={20} height={20} onClick={addViewd}></FireIcon>
								</div>
							)
						default:
							break;
					}
				})}
				{
					!!comments_count && <div className='flex space-x-1'>
						<span className='font-extrabold'>{comments_count}</span>
						<ChatBubbleOvalLeftEllipsisIcon color='gray' width={20} height={20} onClick={addViewd}></ChatBubbleOvalLeftEllipsisIcon>
					</div>
				}
			</div>
		</div>
	)
}

export default ListItem;