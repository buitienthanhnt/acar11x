import Slider from "react-slick";
import Title from "./Title";
import { CurrencyDollarIcon } from "@heroicons/react/24/solid";
import { formatCurrency } from '../Helper/NumberHelper';

const HorizonSlick = ({ items, title, onClick }: { items: any[], title?: string, onClick?: (item: any) => void }) => {
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
				`}
				{/* // .slick-arrow{background-color: gray; border-radius: 100%} */}
			</style>
			<Title title={title || 'Đề xuất'}></Title>
			<Slider {...settings} className='gap-x-2 flex '>
				{items.map((item, index) => {
					return <CarouselItem key={index} value={item} onClick={onClick} />
				})}
			</Slider>
		</div>
	);
}

const CarouselItem = ({ value, onClick }: { value: { name: string, price?: number, image_path?: string }, onClick?: (item: any) => void }) => {

	return (
		<div onClick={() => {
			onClick?.(value);
		}}>
			<div className='border-1 border-gray-200 border rounded-[4px] shadow-md hover:shadow-lg'>
				<div className='relative h-full w-full overflow-hidden rounded-md group p-4'>
					<div style={{
						backgroundImage: `url('${value.image_path}')`,
						backgroundPosition: 'center',
						backgroundSize: 'cover',
					}}
						className={`h-[240px] md:h-[360px] relative w-full transition-transform duration-700 ease-out group-hover:scale-125`}>
					</div>
				</div>
				<div className="left-2 md:left-10 bottom-2 md:bottom-10 p-1 space-y-1">
					<h4 className="row-span-2 text-xl font-semibold ">{value.name}</h4>
					{value.price && <div className="flex">
						<CurrencyDollarIcon className="size-5"></CurrencyDollarIcon>
						<p className="italic text-gray-900 font-semibold text-sm md:text-md">&nbsp;{formatCurrency(value.price)}</p>
					</div>}
				</div>
			</div>
		</div>
	)
}

export default HorizonSlick;