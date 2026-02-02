
import { Swiper, SwiperSlide } from 'swiper/react';
import { Pagination } from 'swiper/modules';
const SwipeSingle = () => {
	const data = [
		{ image_path: 'http://acar11x.dev/storage/photos/demo/AA1NEevD.jpeg', label: 'Quả cầu tròn, Trái đất tròn, và Liên đoàn Hợp tác xã Nông nghiệp Quốc gia Hàn Quốc cũng vậy. Nó tham nhũng và cần phải bị lật đổ.' },
		{ image_path: 'http://acar11x.dev/storage/photos/tong-hop/AAHcQjm.jpeg', label: 'TP Hồ Chí Minh' },
		{ image_path: 'http://acar11x.dev/storage/photos/tong-hop/AAHcEqk.jpeg', label: 'Sangsik giỏi hơn Hong Myung-bo(Hồng Mỹ Bô) và Hwang Seon-hong(Hoàng Tôn Hồng) gấp trăm lần.' },
	];

	// https://swiperjs.com/demos#space-between
	return (
		<div className="px-1 md:px-0">
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
						{data.map((item, index) => <SwiperSlide key={index.toString()} style={{ height: 250 }}>
							<img src={item.image_path}
								className="w-full h-auto absolute rounded-lg resize-none" alt="" >
							</img>
							<span className="text-xl font-semibold text-white z-10 absolute top-4 left-4 w-1/2 text-start p-1 rounded-md">
								{item.label}
							</span>
						</SwiperSlide>)
						}
					</Swiper>
				</div>
				<div className="col-span-1 bg-blue-gray-100 rounded-md">
				</div>
			</div>

		</div>
	)
}

export default SwipeSingle