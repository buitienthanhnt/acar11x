import Slider from "react-slick";
import Title from "./Title";
import { Link } from "@inertiajs/react";
import { sprintf } from "sprintf-js";
import Urls from "../network/Urls";
import { CurrencyDollarIcon } from "@heroicons/react/24/solid";

const HorizonSlick = ({ items }) => {
	const settings = {
		dots: true,
		infinite: true,
		speed: 500,
		slidesToShow: 4,
		slidesToScroll: 4,
		initialSlide: 0,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,
					dots: true
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					initialSlide: 2
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	};

	if (!items) {
		return null;
	}
	return (
		<div className="slider-container py-2 space-y-2">
			<style>
				{`
					.slick-slide { padding: 0 15px;}
					.slick-arrow{background-color: gray; border-radius: 100%}
				`}
			</style>
			<Title title='danh sach de xuat'></Title>
			<Slider {...settings} className='gap-x-2 flex '>
				{items.map((item, index) => {
					return <CarouselItem key={index} value={item} />
				})}
			</Slider>
		</div>
	);
}

const CarouselItem = ({ value }) => {
	return (
		<Link href={sprintf(Urls.bookDetail, [value.id])}>
			<div className='border-1 border-gray-200 border rounded-[4px] shadow-md hover:shadow-lg'>
				<div className='relative h-full w-full overflow-hidden rounded-md group p-4'>
					<div style={{ backgroundImage: `url('${value.image_path}')` }}
						className={`h-[240px] md:h-[360px] relative w-full bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-125`}>
					</div>
				</div>
				<div row-spans-2 className="left-2 md:left-10 bottom-2 md:bottom-10 p-1 space-y-1">
					<h4 className="row-span-2 text-xl font-semibold ">{value.name}</h4>
					<div className="flex">
						<CurrencyDollarIcon className="size-5 text-purple-500"></CurrencyDollarIcon>
						<p className="italic text-purple-600 font-semibold text-sm md:text-md">{value.price}.000 vnd</p>
					</div>
				</div>
			</div>
		</Link>
	)
}

export default HorizonSlick;