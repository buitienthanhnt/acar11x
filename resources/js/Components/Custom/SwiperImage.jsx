import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination } from 'swiper/modules';
import ImagePage from "./ImagePage";
import listPage from "@/data/pageList";

// https://swiperjs.com/react
// https://swiperjs.com/demos#navigation
export default function SwiperImage(params) {
	const pagination = {
		clickable: true,
		renderBullet: function (index, className) {
			return '<span class="' + className + '">' + (index + 1) + '</span>';
		},
	};

	return (
		<div className={`${params?.className}`}>
			<style>
			</style>
			<Swiper navigation={true} pagination={pagination} modules={[Pagination, Navigation]} className="mySwiper">
				{listPage.map((item, index) => <SwiperSlide key={index}>
					<SwiperImageItem item={item}></SwiperImageItem>
				</SwiperSlide>)}
			</Swiper>
		</div>
	)
}

function SwiperImageItem({ item }) {
	return (
		<div className="aspect-video max-h-[450px] w-full md:h-1/3 flex justify-center items-center content-center">
			<ImagePage source={item.image_path} className={'w-full h-auto rounded-md'}></ImagePage>
		</div>
	)
}