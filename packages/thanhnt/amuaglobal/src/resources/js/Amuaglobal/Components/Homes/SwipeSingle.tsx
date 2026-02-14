
import { Swiper, SwiperSlide } from 'swiper/react';
import { Pagination } from 'swiper/modules';
import usePageProps from '../../hooks/usePageProps';
import { Deferred, Link } from '@inertiajs/react';

const SwipeSingle = () => {
	const { trend } = usePageProps();

	// https://swiperjs.com/demos#space-between
	return (
		<Deferred data="trend" fallback={<div>Loading...</div>}>
			<div className="grid grid-cols-5 gap-1">
				<div className="col-span-5 md:col-span-4">
					<Swiper
						spaceBetween={30}
						pagination={{
							clickable: true,
						}}
						modules={[Pagination]}
						className="SwipeSingle"
					>
						{trend && trend.map((item: any, index: number) =>
							<SwiperSlide key={index.toString()} style={{ height: 250 }}>
								<img src={item.image_path}
									className="w-full h-auto absolute rounded-lg resize-none" alt="" >
								</img>
								<Link href={''} className='absolute w-full h-full z-20'>
								</Link>
								<span className="text-xl font-semibold text-white z-10 absolute top-4 left-4 w-1/2 text-start p-1 rounded-md">
									{item.name}
								</span>
							</SwiperSlide>
						)
						}
					</Swiper>
				</div>
				<div className="col-span-1 bg-blue-gray-100 rounded-md">
				</div>
			</div>
		</Deferred>
	)
}

export default SwipeSingle